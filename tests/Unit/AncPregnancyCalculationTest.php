<?php

namespace Tests\Unit;

use App\Modules\ANC\Models\AncPatient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AncPregnancyCalculationTest extends TestCase
{
    use RefreshDatabase;

    public function test_calculate_hpl_formula_naegele(): void
    {
        // Rumus: tgl + 7, bulan - 3, tahun + 1
        // Kasus 1: Bulan 5 (Mei) -> 10 + 7 = 17, 5 - 3 = 2 (Feb), 2026 + 1 = 2027
        $hpl1 = AncPatient::calculateHpl('2026-05-10');
        $this->assertEquals('2027-02-17', $hpl1);

        // Kasus 2: Bulan 1 (Januari) -> 15 + 7 = 22, 1 + 9 = 10 (Okt), 2026
        $hpl2 = AncPatient::calculateHpl('2026-01-15');
        $this->assertEquals('2026-10-22', $hpl2);

        // Kasus 3: Bulan 11 (November) dengan rollover hari -> 25 + 7 = 32 -> 1 Sept 2026
        $hpl3 = AncPatient::calculateHpl('2025-11-25');
        $this->assertEquals('2026-09-01', $hpl3);

        // Kasus 4: Bulan 3 (Maret) -> 4 + 7 = 11, 3 + 9 = 12 (Des), 2026
        $hpl4 = AncPatient::calculateHpl('2026-03-04');
        $this->assertEquals('2026-12-11', $hpl4);
    }

    public function test_calculate_usia_kehamilan(): void
    {
        // 189 hari / 7 = 27 minggu
        $uk = AncPatient::calculateUsiaKehamilan('2026-05-10', '2026-11-15');
        $this->assertEquals(27, $uk);

        // 70 hari / 7 = 10 minggu
        $uk2 = AncPatient::calculateUsiaKehamilan('2026-09-06', '2026-11-15');
        $this->assertEquals(10, $uk2);
    }

    public function test_detect_faktor_risiko_klinis(): void
    {
        $data = [
            'lila' => 21.5, // KEK (< 23.5)
            'hb' => '9.5', // Anemia (< 11.0)
            'tekanan_darah_sistolik' => 145, // HDK (>= 140)
            'umur' => 16, // Primi Muda (< 17)
            'tinggi_badan' => 142, // TB Kurang (< 145)
            'para' => 4, // Grande Multi
            'abortus' => 1, // Riwayat Abortus
        ];

        $risks = AncPatient::detectFaktorRisiko($data);

        $this->assertStringContainsString('KEK', $risks);
        $this->assertStringContainsString('Anemia', $risks);
        $this->assertStringContainsString('HDK', $risks);
        $this->assertStringContainsString('Primi Muda', $risks);
        $this->assertStringContainsString('TB < 145 cm', $risks);
        $this->assertStringContainsString('Grande Multi', $risks);
        $this->assertStringContainsString('Riwayat Abortus', $risks);
    }

    public function test_auto_fill_on_model_saving(): void
    {
        $patient = AncPatient::create([
            'nama_lengkap' => 'Ny. Dewi Lestari',
            'hpht' => '2026-03-04',
            'lila' => 22.0,
            'hb' => '10.2',
        ]);

        // HPL harus terisi otomatis dengan rumus
        $this->assertEquals('2026-12-11', $patient->hpl->format('Y-m-d'));

        // Usia kehamilan harus terisi otomatis
        $this->assertNotNull($patient->usia_kehamilan);

        // Faktor risiko harus mendeteksi KEK dan Anemia
        $this->assertStringContainsString('KEK', $patient->faktor_risiko);
        $this->assertStringContainsString('Anemia', $patient->faktor_risiko);
        $this->assertEquals('Risiko Tinggi', $patient->status_risti);
    }

    public function test_artisan_sync_pregnancy_command(): void
    {
        // Buat record tanpa hpl dan usia_kehamilan secara direct DB
        \DB::table('anc_patients')->insert([
            'nama_lengkap' => 'Ny. Aminah',
            'hpht' => '2026-03-04',
            'hpl' => null,
            'usia_kehamilan' => null,
            'lila' => 21.0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->artisan('anc:sync-pregnancy')
            ->expectsOutputToContain('Selesai! 1 data ibu hamil berhasil disinkronkan.')
            ->assertExitCode(0);

        $updated = AncPatient::where('nama_lengkap', 'Ny. Aminah')->first();
        $this->assertEquals('2026-12-11', $updated->hpl->format('Y-m-d'));
        $this->assertNotNull($updated->usia_kehamilan);
        $this->assertStringContainsString('KEK', $updated->faktor_risiko);
    }
}
