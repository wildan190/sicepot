<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiDashboardService
{
    protected string $apiKey;
    protected string $model;
    protected string $apiUrl;

    protected array $allowedTables = [
        'anc_patients',
        'tb_patients',
    ];

    protected array $tableSchemas = [
        'anc_patients' => [
            'id', 'nama_lengkap', 'nik', 'no_rekam_medis', 'no_bpjs', 'umur',
            'kabupaten', 'kecamatan', 'kelurahan', 'alamat_lengkap',
            'gravida', 'para', 'abortus', 'usia_kehamilan', 'hpht', 'hpl',
            'kunjungan_ke', 'jenis_kunjungan', 'berat_badan', 'tinggi_badan', 'lila',
            'tekanan_darah_sistolik', 'tekanan_darah_diastolik', 'tinggi_fundus_uteri',
            'hb', 'status_anemia', 'gds', 'protein_urine', 'hbsag', 'hiv_status', 'sifilis_status',
            'faktor_risiko', 'status_risti', 'skor_poedji_rochjati', 'kategori_poedji_rochjati',
            'rekomendasi_faskes', 'nama_suami', 'no_telepon', 'bulan', 'tahun',
            'fasyankes_name', 'created_at',
        ],
        'tb_patients' => [
            'id', 'report_type', 'nama_lengkap', 'nik', 'no_rekam_medis', 'no_bpjs',
            'no_reg_sitb', 'no_reg_terduga', 'no_reg_pasien',
            'umur', 'kategori_usia', 'jenis_kelamin', 'pekerjaan',
            'kabupaten', 'kecamatan', 'kelurahan', 'alamat_lengkap',
            'no_telepon', 'nama_pelapor', 'batuk_2_minggu', 'bb_turun', 'keringat_malam', 'kontak_tb', 'sudah_pengobatan',
            'bulan', 'tanggal_mulai_pengobatan', 'status_pengobatan',
            'tipe_diagnosis', 'lokasi_anatomi', 'riwayat_pengobatan',
            'status_hiv', 'riwayat_dm', 'hasil_tcm', 'hasil_mikroskopis',
            'hasil_diagnosis', 'hasil_akhir_pengobatan',
            'fasyankes_name', 'created_at',
        ],
    ];

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key', env('GEMINI_API_KEY'));
        $this->model  = config('services.gemini.model', env('GEMINI_MODEL', 'gemini-2.5-flash'));
        $this->apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent";
    }

    public function generate(string $prompt): array
    {
        if (empty($this->apiKey)) {
            throw new \Exception('GEMINI_API_KEY belum dikonfigurasi pada file .env');
        }

        $systemPrompt = $this->buildSystemPrompt();
        $dashboardConfig = $this->callGemini($systemPrompt, $prompt);

        if (! $dashboardConfig) {
            throw new \Exception('Gagal mendapatkan konfigurasi dashboard dari Gemini AI.');
        }

        if (! isset($dashboardConfig['kpis']) || ! is_array($dashboardConfig['kpis'])) {
            $dashboardConfig['kpis'] = [];
        }
        if (! isset($dashboardConfig['charts']) || ! is_array($dashboardConfig['charts'])) {
            $dashboardConfig['charts'] = [];
        }
        if (! isset($dashboardConfig['insights']) || ! is_array($dashboardConfig['insights'])) {
            $dashboardConfig['insights'] = [];
        }

        foreach ($dashboardConfig['kpis'] as &$kpi) {
            try {
                $rawVal = $this->safeQuery($kpi['query'] ?? '');
                $kpi['value'] = is_numeric($rawVal) ? (float) $rawVal : $rawVal;
            } catch (\Throwable $e) {
                Log::warning('AI Dashboard KPI query error', ['q' => $kpi['query'] ?? '', 'err' => $e->getMessage()]);
                $kpi['value'] = 0;
            }
            unset($kpi['query']);
        }
        unset($kpi);

        foreach ($dashboardConfig['charts'] as &$chart) {
            try {
                $rows = $this->safeQueryRows($chart['query'] ?? '');
                $chart['labels'] = array_map(fn($r) => (string)($r['label'] ?? '-'), $rows);
                $chart['data']   = array_map(fn($r) => is_numeric($r['value'] ?? null) ? (float)$r['value'] : 0, $rows);
            } catch (\Throwable $e) {
                Log::warning('AI Dashboard Chart query error', ['q' => $chart['query'] ?? '', 'err' => $e->getMessage()]);
                $chart['labels'] = [];
                $chart['data']   = [];
            }
            unset($chart['query']);
        }
        unset($chart);

        return $dashboardConfig;
    }

    protected function buildSystemPrompt(): string
    {
        $schemaDesc = '';
        foreach ($this->tableSchemas as $table => $cols) {
            $schemaDesc .= "\nTable `{$table}`: " . implode(', ', $cols);
        }

        return <<<PROMPT
Kamu adalah AI analis data kesehatan Indonesia untuk sistem SICEPOT (Satu Data Kesehatan Wilayah).
Kamu memiliki akses ke database SQLite dengan skema aktual berikut:{$schemaDesc}

PANDUAN NILAI KOLOM DALAM DATABASE:
1. Tabel `anc_patients` (Data Ibu Hamil):
   - `status_risti`: 'Risiko Tinggi' atau 'Normal'
   - `kategori_poedji_rochjati`: 'KRR' (Risiko Rendah), 'KRT' (Risiko Tinggi), 'KRST' (Risiko Sangat Tinggi)
   - `lila`: Ukuran LiLA dalam cm (contoh: LiLA < 23.5 cm mengindikasikan Kurang Energi Kronis / KEK)
   - `faktor_risiko`: teks berisi risiko seperti '%KEK%', '%DM%', '%HDK%', '%BSC%' (Bekas Sesar), '%ANEMIA%'
   - `hb`: Kadar hemoglobin (angka atau string, anemia jika Hb < 11)
   - `tekanan_darah_sistolik` & `tekanan_darah_diastolik`: Tekanan darah (hipertensi jika sistolik >= 140 atau diastolik >= 90)
   - `bulan`: Nama bulan dalam bahasa Indonesia ('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember')
   - `tahun`: Tahun register ('2025', '2026')
   - `kecamatan`, `kelurahan`, `kabupaten`: Wilayah pasien

2. Tabel `tb_patients` (Data Pasien & Terduga TBC):
   - CATATAN PENTING: Nama tabel adalah `tb_patients` (BUKAN `tbc_patients`!).
   - `report_type`: 'tb_03' (Kasus TBC dalam pengobatan) atau 'tb_06' (Register Terduga TBC)
   - `hasil_diagnosis`: 'Terkonfirmasi TBC', 'Terduga', 'Bukan TBC'
   - `tipe_diagnosis`: 'Terkonfirmasi bakteriologis', 'Terdiagnosis klinis'
   - `hasil_tcm`: 'Rif Sen' (Sensitif Rifampisin), 'Rif Res' (Resisten Rifampisin / TB RO), 'Neg' (Negatif), 'ERROR'
   - `status_pengobatan`: 'Sesuai standar', 'Tidak sesuai standar'
   - `status_hiv`: 'ODHIV', 'Bukan ODHIV', 'Tidak diketahui'
   - `riwayat_dm`: 'Ya', 'Tidak'
   - `jenis_kelamin`: 'L' (Laki-laki), 'P' (Perempuan)
   - `bulan`: Nama bulan dalam bahasa Indonesia ('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember')
   - `lokasi_anatomi`: 'TBC Paru', 'TBC Ekstra Paru'
   - `kecamatan`, `kelurahan`, `kabupaten`: Wilayah pasien

TUGAS:
Berdasarkan permintaan user, hasilkan konfigurasi dashboard dalam format JSON STRICT berikut:
{
  "title": "Judul dashboard singkat dan relevan",
  "description": "Deskripsi singkat apa yang dianalisis",
  "kpis": [
    {
      "label": "Label KPI",
      "query": "SELECT COUNT(*) as value FROM tabel WHERE ...",
      "color": "blue|green|red|amber|violet|teal|rose|pink|slate",
      "icon": "users|chart|warning|heart|clipboard|trending"
    }
  ],
  "charts": [
    {
      "type": "bar|line|doughnut",
      "title": "Judul chart",
      "query": "SELECT kolom as label, COUNT(*) as value FROM tabel GROUP BY kolom ORDER BY value DESC LIMIT 15"
    }
  ],
  "insights": [
    "Insight naratif 1 dalam bahasa Indonesia yang bermakna klinis/kebijakan",
    "Insight naratif 2 dalam bahasa Indonesia yang bermakna klinis/kebijakan"
  ]
}

ATURAN QUERY SQL (WAJIB DIIKUTI):
- HANYA gunakan tabel: `anc_patients` dan/atau `tb_patients`. DILARANG menggunakan tabel `tbc_patients`!
- Hanya boleh perintah SELECT. DILARANG INSERT, UPDATE, DELETE, DROP, ALTER, CREATE, TRUNCATE.
- Untuk KPI: query harus menghasilkan tepat 1 baris dengan kolom bernama `value` (contoh: `SELECT COUNT(*) as value FROM tb_patients WHERE hasil_diagnosis = 'Terkonfirmasi TBC'`).
- Untuk Chart: query harus menghasilkan 2 kolom yaitu `label` dan `value` (contoh: `SELECT kelurahan as label, COUNT(*) as value FROM tb_patients WHERE kelurahan IS NOT NULL AND kelurahan != '' GROUP BY kelurahan ORDER BY value DESC LIMIT 10`).
- Pastikan filter `WHERE kolom IS NOT NULL AND kolom != ''` jika melakukan GROUP BY agar chart tidak menampilkan label kosong.
- Buat 3-6 KPI dan 1-3 chart yang paling informatif sesuai permintaan user.
- Buat 2-4 poin insights naratif yang mendalam, informatif, dan actionable.

PENTING: Kembalikan HANYA JSON murni tanpa markdown, tanpa backtick kode ```json, tanpa teks pengantar maupun penutup.
PROMPT;
    }

    protected function callGemini(string $systemPrompt, string $userPrompt): ?array
    {
        try {
            $response = Http::timeout(60)->post("{$this->apiUrl}?key={$this->apiKey}", [
                'system_instruction' => [
                    'parts' => [['text' => $systemPrompt]],
                ],
                'contents' => [
                    [
                        'role'  => 'user',
                        'parts' => [['text' => $userPrompt]],
                    ],
                ],
                'generationConfig' => [
                    'temperature'      => 0.2,
                    'maxOutputTokens'  => 4096,
                    'responseMimeType' => 'application/json',
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('Gemini connection error', ['message' => $e->getMessage()]);
            throw new \Exception('Koneksi ke Google Gemini AI gagal: ' . $e->getMessage());
        }

        if (! $response->successful()) {
            $errorBody = $response->body();
            Log::error('Gemini API error', ['status' => $response->status(), 'body' => $errorBody]);
            $decoded = json_decode($errorBody, true);
            $msg = $decoded['error']['message'] ?? 'Status ' . $response->status();
            throw new \Exception('Gemini API Error: ' . $msg);
        }

        $body = $response->json();
        $text = $body['candidates'][0]['content']['parts'][0]['text'] ?? null;

        if (! $text) {
            return null;
        }

        $text = trim(preg_replace('/^```(?:json)?\s*|\s*```$/m', '', $text));
        return json_decode($text, true);
    }

    protected function safeQuery(string $sql): int|float|string
    {
        $this->validateQuery($sql);
        $result = DB::selectOne($sql);
        if (! $result) return 0;
        $arr = (array) $result;
        return array_values($arr)[0] ?? 0;
    }

    protected function safeQueryRows(string $sql): array
    {
        $this->validateQuery($sql);
        $rows = DB::select($sql);
        return array_map(fn($r) => (array) $r, $rows);
    }

    protected function validateQuery(string $sql): void
    {
        $sql = trim($sql);
        if (empty($sql)) throw new \InvalidArgumentException('Query kosong.');

        if (! preg_match('/^SELECT\s/i', $sql)) {
            throw new \InvalidArgumentException('Hanya query SELECT yang diizinkan.');
        }

        $dangerous = ['INSERT', 'UPDATE', 'DELETE', 'DROP', 'ALTER', 'CREATE', 'TRUNCATE', 'EXEC', '--'];
        foreach ($dangerous as $kw) {
            if (stripos($sql, $kw) !== false) {
                throw new \InvalidArgumentException("Keyword berbahaya: {$kw}");
            }
        }

        $mentioned = [];
        foreach ($this->allowedTables as $table) {
            if (stripos($sql, $table) !== false) $mentioned[] = $table;
        }
        if (empty($mentioned)) {
            throw new \InvalidArgumentException('Query tidak menyebut tabel yang diizinkan.');
        }
    }
}
