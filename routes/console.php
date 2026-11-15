<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('anc:auto-birth-report', function () {
    $this->info("Menjalankan Auto Birth Report untuk ibu hamil yang HPL-nya sudah lewat...");
    $service = app(\App\Modules\ANC\Services\DashboardService::class);
    $count = $service->autoReportOverdueBirths();
    $this->info("Selesai! {$count} data ibu hamil yang lewat HPL berhasil dicatat bersalin secara otomatis.");
})->purpose('Auto birth report untuk ibu hamil yang HPL-nya sudah lewat dari 1 hari');

Artisan::command('anc:sync-pregnancy {--force : Timpa nilai usia_kehamilan dan HPL yang sudah ada}', function () {
    $force = $this->option('force');
    $this->info("Memulai sinkronisasi data kehamilan (HPHT -> HPL, Usia Kehamilan, Faktor Risiko)...");

    // Jalankan auto birth report terlebih dahulu
    $service = app(\App\Modules\ANC\Services\DashboardService::class);
    $autoBirthCount = $service->autoReportOverdueBirths();
    if ($autoBirthCount > 0) {
        $this->line("- Auto Birth Report: {$autoBirthCount} ibu hamil yang lewat HPL telah dicatat bersalin.");
    }

    $count = 0;
    $updatedHpl = 0;
    $updatedUk = 0;
    $updatedFr = 0;

    \App\Modules\ANC\Models\AncPatient::chunk(100, function ($patients) use (&$count, &$updatedHpl, &$updatedUk, &$updatedFr, $force) {
        foreach ($patients as $p) {
            $changed = false;

            // 1. HPHT -> HPL rumus: tgl + 7, bulan - 3, tahun + 1
            if (!empty($p->hpht)) {
                if ($force || empty($p->getRawOriginal('hpl'))) {
                    $calculatedHpl = \App\Modules\ANC\Models\AncPatient::calculateHpl($p->hpht);
                    if ($calculatedHpl && $p->getRawOriginal('hpl') !== $calculatedHpl) {
                        $p->hpl = $calculatedHpl;
                        $changed = true;
                        $updatedHpl++;
                    }
                }

                // 2. Usia Kehamilan dari HPHT
                if ($force || empty($p->getRawOriginal('usia_kehamilan'))) {
                    $uk = \App\Modules\ANC\Models\AncPatient::calculateUsiaKehamilan($p->hpht, $p->tanggal_kunjungan);
                    if ($uk !== null && (string)$p->getRawOriginal('usia_kehamilan') !== (string)$uk) {
                        $p->usia_kehamilan = (string) $uk;
                        $changed = true;
                        $updatedUk++;
                    }
                }
            }

            // 3. Faktor Risiko klinis
            $detected = \App\Modules\ANC\Models\AncPatient::detectFaktorRisiko($p);
            if (!empty($detected)) {
                $currentFr = (string) $p->getRawOriginal('faktor_risiko');
                if (empty($currentFr)) {
                    $p->faktor_risiko = $detected;
                    $changed = true;
                    $updatedFr++;
                } else {
                    $existing = array_map('trim', explode(',', $currentFr));
                    $new = array_map('trim', explode(',', $detected));
                    $merged = array_unique(array_filter(array_merge($existing, $new)));
                    $mergedStr = implode(', ', $merged);
                    if ($mergedStr !== $currentFr) {
                        $p->faktor_risiko = $mergedStr;
                        $changed = true;
                        $updatedFr++;
                    }
                }
            }

            // 4. Update Poedji Rochjati
            $assessment = \App\Modules\ANC\Services\RiskAssessmentService::calculate($p->toArray());
            if ($p->skor_poedji_rochjati !== $assessment['skor'] || $p->kategori_poedji_rochjati !== $assessment['kategori']) {
                $p->skor_poedji_rochjati = $assessment['skor'];
                $p->kategori_poedji_rochjati = $assessment['kategori'];
                $p->rekomendasi_faskes = $assessment['rekomendasi_faskes'];
                if (empty($p->status_risti) || $p->status_risti === 'Normal') {
                    if ($assessment['kategori'] !== 'KRR') {
                        $p->status_risti = 'Risiko Tinggi';
                    }
                }
                $changed = true;
            }

            if ($changed) {
                $p->save();
                $count++;
            }
        }
    });

    $this->info("Selesai! {$count} data ibu hamil berhasil disinkronkan.");
    $this->line("- HPL diperbarui: {$updatedHpl}");
    $this->line("- Usia Kehamilan diisi: {$updatedUk}");
    $this->line("- Faktor Risiko diperbarui: {$updatedFr}");
})->purpose('Sinkronisasi auto-hitung HPL, Usia Kehamilan, dan Faktor Risiko untuk seluruh data ibu hamil');
