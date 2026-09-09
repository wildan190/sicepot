<?php

namespace App\Modules\ANC\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\ANC\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(protected DashboardService $service) {}

    /**
     * Display the ANC (Ibu Hamil) Dashboard.
     */
    public function index(Request $request)
    {
        $stats         = $this->service->getStatsData($request);
        $kabupatenList = $this->service->getKabupatenList();

        return view('anc.dashboard', array_merge($stats, [
            'kabupatenList'     => $kabupatenList,
            'selectedKabupaten' => $request->input('kabupaten', ''),
            'selectedKelurahan' => $request->input('kelurahan', ''),
            'selectedBulan'     => $request->input('bulan', ''),
        ]));
    }

    /**
     * API: Filtered stats & chart data (JSON).
     */
    public function statsJson(Request $request)
    {
        return response()->json($this->service->getStatsData($request));
    }

    /**
     * API: Kelurahan list filtered by kabupaten.
     */
    public function kelurahanList(Request $request)
    {
        return response()->json(
            $this->service->getKelurahanList($request->input('kabupaten'))
        );
    }

    /**
     * API: Kecamatan list filtered by kabupaten.
     */
    public function kecamatanList(Request $request)
    {
        return response()->json(
            $this->service->getKecamatanList($request->input('kabupaten'))
        );
    }

    /**
     * API: Per-kecamatan GIS map data with RISTI detection layers.
     */
    public function mapData(Request $request)
    {
        return response()->json(
            $this->service->getMapData($request)
        );
    }

    /**
     * Export ANC Cohort Patient Data to Formatted Excel.
     */
    public function exportExcel(Request $request, \App\Modules\ANC\Services\ExportService $exportService)
    {
        $filePath = $exportService->exportExcel($request);
        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    /**
     * Display Executive SPM Dinkes Report (Print-friendly).
     */
    public function executiveReport(Request $request)
    {
        $stats = $this->service->getStatsData($request);
        return view('anc.executive-report', array_merge($stats, [
            'selectedKabupaten' => $request->input('kabupaten', ''),
            'selectedKelurahan' => $request->input('kelurahan', ''),
            'selectedBulan'     => $request->input('bulan', ''),
        ]));
    }

    /**
     * AI: Parse free-text prompt into structured ANC patient fields.
     * Coerces types so the output always matches StorePatientRequest validation.
     */
    public function aiParsePatient(\Illuminate\Http\Request $request, \App\Services\GeminiAiService $ai)
    {
        $request->validate(['prompt' => 'required|string|min:10|max:2000']);

        $result = $ai->parseAncPatientFromPrompt($request->input('prompt'));

        if (!$result['success']) {
            return response()->json($result);
        }

        $data = $result['data'] ?? [];

        // ── Normalise kunjungan_ke: integer → "K{n}" string ──────────────────
        if (isset($data['kunjungan_ke'])) {
            $k = $data['kunjungan_ke'];
            if (is_int($k) || (is_string($k) && ctype_digit(trim($k)))) {
                $data['kunjungan_ke'] = 'K' . ltrim((string) $k, '0');
            }
        }

        // ── Normalise integer-only fields ─────────────────────────────────────
        foreach (['gravida', 'para', 'abortus', 'umur', 'skor_poedji_rochjati'] as $intField) {
            if (isset($data[$intField]) && $data[$intField] !== null && $data[$intField] !== '') {
                $data[$intField] = (int) $data[$intField];
            }
        }

        // ── Normalise numeric/decimal fields ─────────────────────────────────
        foreach (['lila', 'berat_badan', 'tinggi_badan', 'berat_lahir_bayi',
                  'tekanan_darah_sistolik', 'tekanan_darah_diastolik',
                  'tinggi_fundus_uteri', 'denyut_jantung_janin', 'gds'] as $numField) {
            if (isset($data[$numField]) && $data[$numField] !== null && $data[$numField] !== '') {
                $data[$numField] = is_numeric($data[$numField]) ? (float) $data[$numField] : null;
            }
        }

        // ── Normalise hb: keep as string (e.g. "10.5") ───────────────────────
        if (isset($data['hb']) && $data['hb'] !== null) {
            $data['hb'] = (string) $data['hb'];
        }

        // ── Strip fields not in StorePatientRequest to avoid mass-assign issues
        $allowed = [
            'nama_lengkap','nama_suami','no_telepon','tanggal_lahir','nik','no_rekam_medis',
            'umur','gravida','para','abortus','usia_kehamilan','hpht','hpl',
            'kunjungan_ke','tanggal_kunjungan','kabupaten','kecamatan','kelurahan','alamat_lengkap',
            'hb','status_anemia','lila','status_risti','skor_poedji_rochjati',
            'kategori_poedji_rochjati','rekomendasi_faskes','calon_pendonor','golongan_darah',
            'faktor_risiko','dirujuk_ke','alasan_rujukan','fasyankes_name','catatan',
            'bulan','jenis_kunjungan','berat_badan','tinggi_badan',
            'tekanan_darah_sistolik','tekanan_darah_diastolik','tinggi_fundus_uteri',
            'presentasi_janin','denyut_jantung_janin','status_imunisasi_tt',
            'gds','protein_urine','hbsag','hiv_status','sifilis_status',
        ];
        $data = array_intersect_key($data, array_flip($allowed));

        return response()->json(['success' => true, 'data' => $data]);
    }

    /**
     * API: AI Triage with Google Gemini for an ANC patient.
     */
    public function aiTriage(Request $request, \App\Modules\ANC\Models\AncPatient $patient, \App\Services\GeminiAiService $aiService)
    {
        $result = $aiService->triageAncPatient($patient->toArray());
        return response()->json($result);
    }

    /**
     * API: Cohort history timeline for an ANC patient.
     */
    public function patientTimeline(\App\Modules\ANC\Models\AncPatient $patient)
    {
        $data = \App\Services\PatientTrackingService::getAncPatientTimeline($patient);
        return response()->json($data);
    }

    /**
     * API: Duplicate screening for an ANC patient.
     */
    public function checkDuplicates(\App\Modules\ANC\Models\AncPatient $patient)
    {
        $duplicates = \App\Services\PatientTrackingService::checkAncDuplicates($patient);
        return response()->json([
            'count'      => count($duplicates),
            'duplicates' => $duplicates,
        ]);
    }
}
