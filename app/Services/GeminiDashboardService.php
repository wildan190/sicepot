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
        'tbc_patients',
    ];

    protected array $tableSchemas = [
        'anc_patients' => [
            'id', 'nama', 'nik', 'no_rm', 'kelurahan', 'kecamatan', 'kabupaten',
            'tgl_hpht', 'tgl_taksiran_lahir', 'umur', 'gravida', 'para', 'abortus',
            'faktor_risiko', 'riwayat_sc', 'hipertensi', 'anemia', 'lila',
            'penyakit_penyerta', 'status', 'bulan_register', 'tahun_register',
            'created_at',
        ],
        'tbc_patients' => [
            'id', 'nama', 'nik', 'kelurahan', 'kecamatan', 'kabupaten',
            'status_terduga', 'status_terkonfirmasi', 'jenis_kelamin', 'umur',
            'hasil_tcm', 'hasil_xray', 'klasifikasi', 'investigasi_kontak',
            'tgl_mulai_oat', 'status_pengobatan', 'bulan', 'tahun',
            'created_at',
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
        $systemPrompt = $this->buildSystemPrompt();
        $dashboardConfig = $this->callGemini($systemPrompt, $prompt);

        if (! $dashboardConfig) {
            throw new \Exception('Gagal mendapatkan respons dari Gemini AI.');
        }

        foreach ($dashboardConfig['kpis'] as &$kpi) {
            try {
                $kpi['value'] = $this->safeQuery($kpi['query'] ?? '');
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
                $chart['labels'] = array_column($rows, 'label');
                $chart['data']   = array_column($rows, 'value');
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
Kamu adalah AI analis data kesehatan Indonesia untuk sistem SICEPOT.
Kamu memiliki akses ke database SQLite dengan skema berikut:{$schemaDesc}

TUGAS:
Berdasarkan permintaan user, hasilkan konfigurasi dashboard dalam format JSON STRICT berikut:
{
  "title": "Judul dashboard singkat",
  "description": "Deskripsi singkat apa yang ditampilkan",
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
      "query": "SELECT kolom as label, COUNT(*) as value FROM tabel GROUP BY kolom ORDER BY value DESC LIMIT 20"
    }
  ],
  "insights": [
    "Insight 1 dalam bahasa Indonesia",
    "Insight 2 dalam bahasa Indonesia"
  ]
}

ATURAN QUERY (WAJIB DIIKUTI):
- Hanya boleh SELECT. DILARANG INSERT, UPDATE, DELETE, DROP, ALTER, CREATE.
- Hanya boleh query dari tabel: anc_patients, tbc_patients.
- Untuk KPI: query harus menghasilkan satu baris dengan kolom bernama `value` (angka).
- Untuk Chart: query harus menghasilkan kolom `label` (string) dan `value` (angka).
- Gunakan SQLite syntax. Gunakan strftime untuk tanggal jika perlu.
- Buat 3-6 KPI dan 1-3 chart yang relevan dengan permintaan user.
- Buat 2-4 insights berbahasa Indonesia yang informatif dan actionable.

PENTING: Kembalikan HANYA JSON murni tanpa markdown, tanpa kode fence, tanpa penjelasan.
PROMPT;
    }

    protected function callGemini(string $systemPrompt, string $userPrompt): ?array
    {
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

        if (! $response->successful()) {
            Log::error('Gemini API error', ['status' => $response->status(), 'body' => $response->body()]);
            return null;
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
