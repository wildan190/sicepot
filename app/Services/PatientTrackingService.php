<?php

namespace App\Services;

use App\Modules\ANC\Models\AncPatient;
use App\Modules\TBC\Models\TbPatient;
use Illuminate\Support\Collection;

class PatientTrackingService
{
    /**
     * Detect duplicates for an ANC Patient across all records.
     * Looks for identical NIK or matching Name + Village.
     */
    public static function checkAncDuplicates(AncPatient $patient): array
    {
        $duplicates = [];

        // 1. Exact NIK match (different record ID)
        if (!empty($patient->nik)) {
            $nikMatches = AncPatient::where('id', '!=', $patient->id)
                ->where('nik', $patient->nik)
                ->get(['id', 'nama_lengkap', 'nik', 'kabupaten', 'kelurahan', 'kunjungan_ke', 'hpl', 'created_at']);

            foreach ($nikMatches as $m) {
                $duplicates[] = [
                    'id'          => $m->id,
                    'type'        => 'NIK Identik',
                    'description' => "Ditemukan NIK sama ({$patient->nik}) di {$m->kelurahan}, {$m->kabupaten} (Kunjungan: {$m->kunjungan_ke})",
                    'patient'     => $m,
                ];
            }
        }

        // 2. Exact Name in same / different village
        if (!empty($patient->nama_lengkap) && strlen($patient->nama_lengkap) >= 4) {
            $nameMatches = AncPatient::where('id', '!=', $patient->id)
                ->where(function ($q) use ($patient) {
                    $q->whereRaw('LOWER(nama_lengkap) = ?', [strtolower($patient->nama_lengkap)]);
                })
                ->where('nik', '!=', (string)$patient->nik)
                ->get(['id', 'nama_lengkap', 'nik', 'kabupaten', 'kelurahan', 'kunjungan_ke', 'hpl', 'created_at']);

            foreach ($nameMatches as $m) {
                $duplicates[] = [
                    'id'          => $m->id,
                    'type'        => 'Nama Serupa (Potensi Ganda)',
                    'description' => "Nama sama '{$m->nama_lengkap}' ditemukan di {$m->kelurahan} (NIK berbeda/kosong)",
                    'patient'     => $m,
                ];
            }
        }

        return $duplicates;
    }

    /**
     * Detect duplicates for a TBC Patient across all records.
     */
    public static function checkTbDuplicates(TbPatient $patient): array
    {
        $duplicates = [];

        // 1. Exact NIK match
        if (!empty($patient->nik)) {
            $nikMatches = TbPatient::where('id', '!=', $patient->id)
                ->where('nik', $patient->nik)
                ->get(['id', 'nama_lengkap', 'nik', 'kabupaten', 'kelurahan', 'report_type', 'status_pengobatan', 'created_at']);

            foreach ($nikMatches as $m) {
                $duplicates[] = [
                    'id'          => $m->id,
                    'type'        => 'NIK Identik',
                    'description' => "Ditemukan NIK sama ({$patient->nik}) di register {$m->report_type} ({$m->kelurahan})",
                    'patient'     => $m,
                ];
            }
        }

        // 2. Exact Name match
        if (!empty($patient->nama_lengkap) && strlen($patient->nama_lengkap) >= 4) {
            $nameMatches = TbPatient::where('id', '!=', $patient->id)
                ->where(function ($q) use ($patient) {
                    $q->whereRaw('LOWER(nama_lengkap) = ?', [strtolower($patient->nama_lengkap)]);
                })
                ->where('nik', '!=', (string)$patient->nik)
                ->get(['id', 'nama_lengkap', 'nik', 'kabupaten', 'kelurahan', 'report_type', 'status_pengobatan', 'created_at']);

            foreach ($nameMatches as $m) {
                $duplicates[] = [
                    'id'          => $m->id,
                    'type'        => 'Nama Serupa (Potensi Pindah Wilayah)',
                    'description' => "Nama sama '{$m->nama_lengkap}' tercatat di {$m->kelurahan}, {$m->kabupaten}",
                    'patient'     => $m,
                ];
            }
        }

        return $duplicates;
    }

    /**
     * Build comprehensive Cohort Timeline for an ANC Patient.
     * Groups visits and records related by NIK or Name.
     */
    public static function getAncPatientTimeline(AncPatient $patient): array
    {
        $related = AncPatient::query()
            ->where(function ($q) use ($patient) {
                if (!empty($patient->nik)) {
                    $q->where('nik', $patient->nik);
                }
                if (!empty($patient->nama_lengkap)) {
                    $q->orWhereRaw('LOWER(nama_lengkap) = ?', [strtolower($patient->nama_lengkap)]);
                }
            })
            ->orderBy('tanggal_kunjungan')
            ->orderBy('id')
            ->get();

        $timeline = [];
        foreach ($related as $rec) {
            $timeline[] = [
                'id'                => $rec->id,
                'is_current'        => $rec->id === $patient->id,
                'title'             => 'Pemeriksaan ' . ($rec->kunjungan_ke ?: 'ANC'),
                'date'              => $rec->tanggal_kunjungan ? $rec->tanggal_kunjungan->format('d M Y') : ($rec->bulan ?: 'Kunjungan'),
                'faskes'            => ($rec->kelurahan ?: 'Puskesmas') . ' (' . ($rec->kabupaten ?: 'Wilayah') . ')',
                'gestational_age'   => $rec->usia_kehamilan ? $rec->usia_kehamilan . ' mgg' : '-',
                'hb'                => $rec->hb ? $rec->hb . ' g/dL' : '-',
                'lila'              => $rec->lila ? $rec->lila . ' cm' : '-',
                'poedji_score'      => $rec->skor_poedji_rochjati ?: 2,
                'poedji_category'   => $rec->kategori_poedji_rochjati ?: 'KRR',
                'status_risti'      => $rec->status_risti ?: 'Normal',
                'rekomendasi'       => $rec->rekomendasi_faskes ?: 'Bidan / Puskesmas',
                'catatan'           => $rec->catatan ?: ($rec->faktor_risiko ?: 'Kondisi terpantau aman'),
            ];
        }

        return [
            'patient'  => $patient,
            'timeline' => $timeline,
            'total_visits' => count($timeline),
        ];
    }

    /**
     * Build Treatment Journey Timeline for a TBC Patient.
     */
    public static function getTbPatientTimeline(TbPatient $patient): array
    {
        $related = TbPatient::query()
            ->where(function ($q) use ($patient) {
                if (!empty($patient->nik)) {
                    $q->where('nik', $patient->nik);
                }
                if (!empty($patient->nama_lengkap)) {
                    $q->orWhereRaw('LOWER(nama_lengkap) = ?', [strtolower($patient->nama_lengkap)]);
                }
            })
            ->orderBy('tanggal_daftar')
            ->orderBy('id')
            ->get();

        $timeline = [];
        foreach ($related as $rec) {
            $timeline[] = [
                'id'                 => $rec->id,
                'is_current'         => $rec->id === $patient->id,
                'report_type'        => $rec->report_type ?: 'TB-03',
                'diagnosis_type'     => $rec->tipe_diagnosis ?: 'TBC Paru',
                'date_start'         => $rec->tanggal_mulai_pengobatan ?: ($rec->tanggal_daftar ?: '-'),
                'treatment_status'   => $rec->status_pengobatan ?: 'Dalam Pengobatan',
                'hasil_tcm'          => $rec->hasil_tcm ?: '-',
                'hasil_mikroskopis'  => $rec->hasil_mikroskopis ?: '-',
                'hasil_akhir'        => $rec->hasil_akhir_pengobatan ?: 'Aktif Terapi',
                'faskes'             => ($rec->kelurahan ?: 'Puskesmas') . ' (' . ($rec->kabupaten ?: 'Kabupaten') . ')',
            ];
        }

        return [
            'patient'  => $patient,
            'timeline' => $timeline,
            'total_episodes' => count($timeline),
        ];
    }
}
