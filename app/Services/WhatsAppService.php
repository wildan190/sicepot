<?php

namespace App\Services;

class WhatsAppService
{
    /**
     * Clean and normalize Indonesian phone numbers into international format (628...).
     */
    public static function normalizePhone(?string $phone): ?string
    {
        if (empty($phone)) {
            return null;
        }

        // Remove all non-numeric characters
        $clean = preg_replace('/\D+/', '', $phone);

        if (empty($clean)) {
            return null;
        }

        // Convert leading 0 to 62
        if (str_starts_with($clean, '0')) {
            $clean = '62' . substr($clean, 1);
        } elseif (str_starts_with($clean, '8')) {
            $clean = '62' . $clean;
        } elseif (str_starts_with($clean, '62')) {
            // Already standard
        } else {
            // Return null if it doesn't look like a valid phone
            if (strlen($clean) < 8) {
                return null;
            }
            $clean = '62' . $clean;
        }

        return $clean;
    }

    /**
     * Generate https://wa.me/ URL with pre-filled message.
     */
    public static function buildUrl(?string $phone, string $message): ?string
    {
        $normalized = self::normalizePhone($phone);
        if (!$normalized) {
            return null;
        }

        return 'https://wa.me/' . $normalized . '?text=' . rawurlencode($message);
    }

    /**
     * Templates for ANC Ibu Hamil
     */
    public static function makeAncReminderMessage(array $patient, string $type = 'anc_checkup'): string
    {
        $nama = $patient['nama_lengkap'] ?? 'Ibu';
        $hpl = !empty($patient['hpl']) ? date('d/m/Y', strtotime($patient['hpl'])) : '-';
        $kelurahan = $patient['kelurahan'] ?? 'Puskesmas';

        switch ($type) {
            case 'h1_alert':
                return "Halo Ibu {$nama},\n\n"
                    . "Pemberitahuan dari Tim Pelayanan KIA {$kelurahan} (SICEPOT):\n"
                    . "Berdasarkan catatan rekam medis, Hari Perkiraan Lahir (HPL) Anda diperkirakan BESOK / Segera ({$hpl}).\n\n"
                    . "Mohon memastikan:\n"
                    . "1. Buku KIA & berkas BPJS/KTP sudah siap.\n"
                    . "2. Tas persalinan ibu & bayi siap dibawa.\n"
                    . "3. Suami/Keluarga siaga mendampingi ke faskes rujukan persalinan.\n\n"
                    . "Bila mengalami kontraksi teratur atau keluar cairan/flek, segera kunjungi fasyankes terdekat. Semoga persalinan lancar & sehat selalu!";

            case 'kek_nutrition':
                $lila = $patient['lila'] ?? '-';
                return "Halo Ibu {$nama},\n\n"
                    . "Pengingat Kesehatan dari Puskesmas {$kelurahan}:\n"
                    . "Berdasarkan hasil pengukuran LiLA Anda ({$lila} cm), Anda memerlukan asupan nutrisi ekstra untuk mendukung tumbuh kembang janin yang optimal.\n\n"
                    . "Mohon rutin mengonsumsi:\n"
                    . "- Makanan tinggi protein (telur, ikan, ayam, tahu, tempe)\n"
                    . "- Makanan Tambahan (PMT) dari puskesmas\n"
                    . "- Tablet Tambah Darah (TTD) 1 tablet setiap malam\n\n"
                    . "Mari bersama wujudkan kehamilan sehat bebas risiko! Jika ada keluhan, jangan ragu berkonsultasi dengan bidan kami.";

            case 'anc_checkup':
            default:
                $kunjungan = $patient['kunjungan_ke'] ?? 'berikutnya';
                return "Halo Ibu {$nama},\n\n"
                    . "Salam hangat dari Petugas Kesehatan Puskesmas {$kelurahan} (SICEPOT).\n"
                    . "Mengingatkan untuk jadwal pemeriksaan kehamilan ({$kunjungan}).\n"
                    . "Pemeriksaan rutin sangat penting untuk memantau kesehatan Ibu dan perkembangan dedek bayi dalam kandungan.\n\n"
                    . "Silakan datang ke Puskesmas/Posyandu terdekat membawa Buku KIA. Terima kasih dan salam sehat selalu!";
        }
    }

    /**
     * Templates for Suami SIAGA
     */
    public static function makeSuamiReminderMessage(array $patient): string
    {
        $suami = $patient['nama_suami'] ?? 'Bapak';
        $istri = $patient['nama_lengkap'] ?? 'Ibu';
        $hpl = !empty($patient['hpl']) ? date('d/m/Y', strtotime($patient['hpl'])) : '-';
        $kelurahan = $patient['kelurahan'] ?? 'Puskesmas';
        $donor = !empty($patient['calon_pendonor']) ? "Calon Pendonor: {$patient['calon_pendonor']}" : "Mohon siapkan 2 calon pendonor darah keluarga";

        return "Halo Bpk. {$suami},\n\n"
            . "Salam dari Tim P4K Puskesmas {$kelurahan} (Program Perencanaan Persalinan & Pencegahan Komplikasi):\n"
            . "Istri tercinta ({$istri}) diperkirakan melahirkan sekitar tanggal {$hpl}.\n\n"
            . "Sebagai Suami SIAGA, mohon pastikan:\n"
            . "- Kendaraan / transportasi siaga menuju faskes.\n"
            . "- Tabungan persalinan & berkas administrasi siap.\n"
            . "- {$donor}.\n\n"
            . "Dukungan dan pendampingan Bapak sangat berarti bagi keselamatan ibu dan buah hati. Terima kasih!";
    }

    /**
     * Templates for TBC Patients
     */
    public static function makeTbReminderMessage(array $patient, string $type = 'oat_daily'): string
    {
        $nama = $patient['nama_lengkap'] ?? 'Bapak/Ibu';
        $kelurahan = $patient['kelurahan'] ?? 'Puskesmas';

        switch ($type) {
            case 'sputum_eval':
                return "Halo {$nama},\n\n"
                    . "Salam dari Petugas Program TBC Puskesmas {$kelurahan} (SICEPOT).\n"
                    . "Mengingatkan bahwa jadwal pemeriksaan dahak evaluasi (kontrol ulang) Anda sudah tiba.\n\n"
                    . "Pemeriksaan dahak berkala ini penting untuk memastikan kuman TBC telah berkurang/hilang dan efektivitas pengobatan Anda.\n\n"
                    . "Silakan datang ke laboratorium Puskesmas untuk pengambilan sediaan dahak. Semangat menuju kesembuhan tuntas!";

            case 'dropout_warning':
                return "PENTING & SEGERA - Petugas TBC Puskesmas {$kelurahan}:\n\n"
                    . "Halo {$nama}, berdasarkan catatan kami, Anda belum mengambil obat / kontrol TBC sesuai jadwal.\n\n"
                    . "PENGOBATAN TBC TIDAK BOLEH PUTUS! Berhenti minum obat berisiko menyebabkan kuman TBC menjadi kebal obat (TBC Resisten Obat/RO) yang pengobatannya jauh lebih sulit.\n\n"
                    . "Mohon SEGERA hubungi atau datang ke Puskesmas hari ini untuk melanjutkan pengobatan Anda. Kami siap mendampingi Anda sampai sembuh!";

            case 'oat_daily':
            default:
                return "Halo {$nama},\n\n"
                    . "Pengingat Harian dari Puskesmas {$kelurahan} (SICEPOT):\n"
                    . "Sudahkah Anda meminum Obat Anti Tuberkulosis (OAT) hari ini?\n\n"
                    . "Pastikan diminum secara teratur sesuai anjuran dokter (pada waktu yang sama setiap hari). Kunci kesembuhan TBC adalah kedisiplinan dan kepatuhan minum obat secara tuntas.\n\n"
                    . "Tetap semangat dan jaga kesehatan bersama keluarga tercinta!";
        }
    }
}
