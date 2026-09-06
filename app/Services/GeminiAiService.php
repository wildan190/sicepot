<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiAiService
{
    protected string $apiKey;
    protected string $model;
    protected string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key', env('GEMINI_API_KEY', ''));
        $this->model  = config('services.gemini.model', 'gemini-2.5-flash');
    }

    /**
     * Send prompt to Gemini API.
     */
    public function generate(string $prompt, ?string $systemInstruction = null): array
    {
        if (empty($this->apiKey)) {
            return [
                'success' => false,
                'message' => 'API Key Google Gemini belum dikonfigurasi pada environment.',
            ];
        }

        $url = $this->baseUrl . $this->model . ':generateContent?key=' . $this->apiKey;

        $body = [
            'contents' => [
                [
                    'role'  => 'user',
                    'parts' => [
                        ['text' => $prompt],
                    ],
                ],
            ],
            'generationConfig' => [
                'temperature'     => 0.2,
                'maxOutputTokens' => 2048,
            ],
        ];

        if ($systemInstruction) {
            $body['systemInstruction'] = [
                'parts' => [
                    ['text' => $systemInstruction],
                ],
            ];
        }

        try {
            $response = Http::timeout(25)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($url, $body);

            if ($response->successful()) {
                $data = $response->json();
                $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Tidak ada analisis yang dihasilkan.';

                return [
                    'success' => true,
                    'content' => $text,
                    'raw'     => $data,
                ];
            }

            Log::error('Gemini API Error', ['status' => $response->status(), 'body' => $response->body()]);

            return [
                'success' => false,
                'message' => 'Gagal menghubungi Gemini API: ' . ($response->json('error.message') ?? $response->body()),
            ];
        } catch (\Exception $e) {
            Log::error('Gemini Exception', ['error' => $e->getMessage()]);

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat menghubungkan ke Gemini: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * AI Triage & Anomaly Detection for ANC Patient
     */
    public function triageAncPatient(array|object $patient): array
    {
        if (is_object($patient) && method_exists($patient, 'toArray')) {
            $patient = $patient->toArray();
        } else {
            $patient = (array) $patient;
        }

        $system = "Anda adalah Asisten Pakar Medis KIA (Kesehatan Ibu dan Anak) dan Kebidanan di Puskesmas Indonesia. "
            . "Tugas Anda adalah menelaah data rekam medis ibu hamil secara kritis, mendeteksi anomali/kejanggalan register, "
            . "menilai risiko komplikasi persalinan, dan memberikan rekomendasi faskes rujukan (BPM/Puskesmas Non-PONED vs Puskesmas PONED vs RS PONEK). "
            . "Gunakan bahasa Indonesia yang profesional, ringkas, dan jelas dalam poin-poin.";

        $prompt = "Tolong lakukan triage cerdas dan deteksi anomali pada data rekam medis ibu hamil berikut:\n\n"
            . "- Nama: " . ($patient['nama_lengkap'] ?? '-') . "\n"
            . "- Umur: " . ($patient['umur'] ?? '-') . " tahun\n"
            . "- NIK: " . ($patient['nik'] ?? '-') . "\n"
            . "- Paritas (G-P-A): G" . ($patient['gravida'] ?? '-') . "P" . ($patient['para'] ?? '-') . "A" . ($patient['abortus'] ?? '-') . "\n"
            . "- Usia Kehamilan: " . ($patient['usia_kehamilan'] ?? '-') . " minggu\n"
            . "- HPHT: " . ($patient['hpht'] ?? '-') . "\n"
            . "- HPL / TP: " . ($patient['hpl'] ?? '-') . "\n"
            . "- Kunjungan: " . ($patient['kunjungan_ke'] ?? '-') . "\n"
            . "- Lingkar Lengan Atas (LiLA): " . ($patient['lila'] ?? '-') . " cm\n"
            . "- Kadar Hemoglobin (Hb): " . ($patient['hb'] ?? '-') . " g/dL\n"
            . "- Status Anemia: " . ($patient['status_anemia'] ?? '-') . "\n"
            . "- Status RISTI: " . ($patient['status_risti'] ?? '-') . "\n"
            . "- Faktor Risiko Tercatat: " . ($patient['faktor_risiko'] ?? '-') . "\n"
            . "- Wilayah: " . ($patient['kelurahan'] ?? '-') . ", " . ($patient['kabupaten'] ?? '-') . "\n\n"
            . "Berikan output terstruktur dengan format:\n"
            . "1. **Deteksi Anomali Data**: (Periksa apakah HPL sinkron dengan HPHT ~40 minggu, apakah umur terlalu muda/tua tapi tidak ditandai RISTI, apakah LiLA < 23.5 tapi belum KEK, dsb)\n"
            . "2. **Tingkat Risiko & Komplikasi Potensial**: (KRR / KRT / KRST dan potensi risiko seperti stunting janin, PJT, perdarahan post-partum, PEB)\n"
            . "3. **Rekomendasi Fasyankes Rujukan**: (Tempat persalinan paling aman: Bidan/Puskesmas vs PONED vs RS PONEK)\n"
            . "4. **Tindakan Intervensi Cepat**: (3 langkah spesifik yang harus diambil bidan/puskesmas sekarang)";

        return $this->generate($prompt, $system);
    }

    /**
     * AI Triage & Anomaly Detection for TBC Patient
     */
    public function triageTbPatient(array|object $patient): array
    {
        if (is_object($patient) && method_exists($patient, 'toArray')) {
            $patient = $patient->toArray();
        } else {
            $patient = (array) $patient;
        }

        $system = "Anda adalah Pakar Epidemiologi Klinis dan Program Penanggulangan Tuberkulosis (TB) Kementerian Kesehatan RI. "
            . "Tugas Anda menganalisis rekam jejak pasien TB, mendeteksi potensi mangkir / putus obat (Lost to Follow Up), "
            . "mengevaluasi kepatuhan terapi OAT, dan memandu investigasi kontak penularan. "
            . "Gunakan bahasa Indonesia yang lugas, terstruktur, dan solutif. Jangan gunakan emoji dalam output respon Anda.";

        $prompt = "Tolong lakukan evaluasi klinis dan deteksi anomali pada data pasien TBC berikut:\n\n"
            . "- Nama: " . ($patient['nama_lengkap'] ?? '-') . "\n"
            . "- Umur: " . ($patient['umur'] ?? '-') . " tahun | Jenis Kelamin: " . ($patient['jenis_kelamin'] ?? '-') . "\n"
            . "- Tipe Laporan: " . ($patient['report_type'] ?? '-') . "\n"
            . "- Tipe Diagnosis: " . ($patient['tipe_diagnosis'] ?? '-') . "\n"
            . "- Hasil TCM / GenXpert: " . ($patient['hasil_tcm'] ?? '-') . "\n"
            . "- Hasil Mikroskopis: " . ($patient['hasil_mikroskopis'] ?? '-') . "\n"
            . "- Status Pengobatan: " . ($patient['status_pengobatan'] ?? '-') . "\n"
            . "- Tanggal Mulai Pengobatan: " . ($patient['tanggal_mulai_pengobatan'] ?? '-') . "\n"
            . "- Riwayat TB: " . ($patient['riwayat_pengobatan'] ?? '-') . "\n"
            . "- Status HIV: " . ($patient['status_hiv'] ?? '-') . " | Riwayat DM: " . ($patient['riwayat_dm'] ?? '-') . "\n"
            . "- Lokasi Anatomi: " . ($patient['lokasi_anatomi'] ?? '-') . "\n"
            . "- Wilayah: " . ($patient['kelurahan'] ?? '-') . ", " . ($patient['kabupaten'] ?? '-') . "\n\n"
            . "Berikan output terstruktur dengan format:\n"
            . "1. **Deteksi Anomali / Kesenjangan Register**: (Kesesuaian diagnosis vs paduan obat, status komorbid HIV/DM, jeda tanggal)\n"
            . "2. **Analisis Risiko Resistensi & Mangkir (Drop-out)**: (Risiko TB-RO / MDR, kegagalan konversi dahak)\n"
            . "3. **Rencana Investigasi Kontak (IK)**: (Prioritas skrining kontak serumah, anak balita, atau lansia di sekitarnya)\n"
            . "4. **Rekomendasi Tindak Lanjut Pengobatan**: (Langkah konkrit bagi PMO / nakes puskesmas)";

        return $this->generate($prompt, $system);
    }
}
