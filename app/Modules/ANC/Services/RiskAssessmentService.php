<?php

namespace App\Modules\ANC\Services;

class RiskAssessmentService
{
    /**
     * Hitung Skor Poedji Rochjati & Rekomendasi Tempat Persalinan
     *
     * Berdasarkan pedoman Kemenkes / Skor Poedji Rochjati:
     * - Skor Awal (Bumil Normal): 2
     * - Kelompok I: Potensi Bahaya (Skor +4 per faktor)
     *   - Primi muda (umur <= 16 th)
     *   - Primi tua (umur >= 35 th hamil pertama)
     *   - Primi tua sekunder (jarak anak terakhir >= 10 th)
     *   - Grande multi (anak >= 4)
     *   - Umur >= 35 th
     *   - Tinggi badan <= 145 cm
     *   - Riwayat abortus
     * - Kelompok II: Ada Bahaya (Skor +4 s/d +8)
     *   - Anemia (Hb < 11) (+4)
     *   - KEK / LILA < 23.5 cm (+4)
     *   - Preeklampsia / Hipertensi (Sistolik >= 140 atau Diastolik >= 90) (+8)
     * - Kelompok III: Ada Bahaya Sangat Besar (Skor +8)
     *   - Bekas Sesar (SC), Perdarahan, Kelainan Letak
     *
     * Klasifikasi:
     * - KRR (Kehamilan Risiko Rendah): Skor 2 -> BPM / Puskesmas
     * - KRT (Kehamilan Risiko Tinggi): Skor 6-10 -> Puskesmas PONED / RS
     * - KRST (Kehamilan Risiko Sangat Tinggi): Skor >= 12 -> RS PONEK / Dokter Spesialis
     */
    public static function calculate(array $data): array
    {
        $score = 2; // Skor dasar untuk setiap kehamilan
        $factors = [];

        $umur = isset($data['umur']) ? (int) $data['umur'] : null;
        $gravida = isset($data['gravida']) ? (int) $data['gravida'] : 1;
        $para = isset($data['para']) ? (int) $data['para'] : 0;
        $abortus = isset($data['abortus']) ? (int) $data['abortus'] : 0;
        $hb = isset($data['hb']) ? (float) str_replace(',', '.', (string)$data['hb']) : null;
        $lila = isset($data['lila']) ? (float) str_replace(',', '.', (string)$data['lila']) : null;
        $tb = isset($data['tinggi_badan']) ? (float) str_replace(',', '.', (string)$data['tinggi_badan']) : null;
        $tdSistolik = isset($data['tekanan_darah_sistolik']) ? (float) $data['tekanan_darah_sistolik'] : null;
        $statusRisti = strtolower($data['status_risti'] ?? '');
        $faktorRisiko = strtolower($data['faktor_risiko'] ?? '');

        // 1. Faktor Umur
        if ($umur !== null) {
            if ($umur < 17) {
                $score += 4;
                $factors[] = 'Primi Muda (Umur < 17 th)';
            } elseif ($umur >= 35) {
                $score += 4;
                $factors[] = 'Umur Hamil Lanjut (≥ 35 th)';
            }
        }

        // 2. Faktor Paritas & Riwayat
        if ($para >= 4) {
            $score += 4;
            $factors[] = 'Grande Multi (Pernah melahirkan ≥ 4 kali)';
        }
        if ($abortus > 0) {
            $score += 4;
            $factors[] = 'Riwayat Abortus/Keguguran';
        }

        // 3. Tinggi Badan & LILA
        if ($tb !== null && $tb > 0 && $tb < 145) {
            $score += 4;
            $factors[] = 'Tinggi Badan Kurang (< 145 cm)';
        }
        if ($lila !== null && $lila > 0 && $lila < 23.5) {
            $score += 4;
            $factors[] = 'Kurang Energi Kronis (LILA < 23.5 cm)';
        }

        // 4. Anemia
        if ($hb !== null && $hb > 0 && $hb < 11.0) {
            $score += 4;
            $factors[] = 'Anemia Gravidarum (Hb < 11 g/dL)';
        }

        // 5. Hipertensi
        if ($tdSistolik !== null && $tdSistolik >= 140) {
            $score += 8;
            $factors[] = 'Hipertensi dalam Kehamilan (TD Sistolik ≥ 140 mmHg)';
        }

        // 6. Riwayat Khusus dari Teks Faktor Risiko
        if (str_contains($faktorRisiko, 'sesar') || str_contains($faktorRisiko, 'sc') || str_contains($faktorRisiko, 'operasi')) {
            $score += 8;
            $factors[] = 'Riwayat Sesar (SC)';
        }
        if (str_contains($faktorRisiko, 'pendarahan') || str_contains($faktorRisiko, 'perdarahan')) {
            $score += 8;
            $factors[] = 'Perdarahan Antepartum';
        }
        if (str_contains($faktorRisiko, 'sungsang') || str_contains($faktorRisiko, 'lintang') || str_contains($faktorRisiko, 'letak sungsang')) {
            $score += 8;
            $factors[] = 'Kelainan Letak Janin';
        }
        if (str_contains($faktorRisiko, 'kembar') || str_contains($faktorRisiko, 'gemelli')) {
            $score += 4;
            $factors[] = 'Kehamilan Kembar';
        }

        // Fallback jika status risti tinggi tapi belum ada skor spesifik
        if (($statusRisti === 'risiko tinggi' || $statusRisti === 'risti') && $score < 6) {
            $score = 6;
            $factors[] = 'Faktor Risiko Klinis Terdeteksi';
        }

        // Klasifikasi & Rekomendasi
        if ($score >= 12) {
            $kategori = 'KRST';
            $kategoriLabel = 'Kehamilan Risiko Sangat Tinggi (KRST)';
            $rekomendasi = 'Rumah Sakit (RS PONEK / Dokter Spesialis Obgyn)';
        } elseif ($score >= 6) {
            $kategori = 'KRT';
            $kategoriLabel = 'Kehamilan Risiko Tinggi (KRT)';
            $rekomendasi = 'Puskesmas PONED / Rumah Sakit Rujukan';
        } else {
            $kategori = 'KRR';
            $kategoriLabel = 'Kehamilan Risiko Rendah (KRR)';
            $rekomendasi = 'Bidan Praktik Mandiri (BPM) / Puskesmas Non-PONED';
        }

        return [
            'skor'              => $score,
            'kategori'          => $kategori,
            'kategori_label'    => $kategoriLabel,
            'rekomendasi_faskes'=> $rekomendasi,
            'factors'           => $factors,
        ];
    }
}
