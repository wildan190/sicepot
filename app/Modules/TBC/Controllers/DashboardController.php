<?php

namespace App\Modules\TBC\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\TBC\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(protected DashboardService $service) {}

    /**
     * Display the TBC Dashboard.
     */
    public function index(Request $request)
    {
        $stats         = $this->service->getStatsData($request);
        $kabupatenList = $this->service->getKabupatenList();

        return view('tb.dashboard', array_merge($stats, [
            'kabupatenList'      => $kabupatenList,
            'selectedKabupaten'  => $request->input('kabupaten', ''),
            'selectedKelurahan'  => $request->input('kelurahan', ''),
            'selectedType'       => $request->input('report_type', ''),
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
     * API: Per-kecamatan GIS map data with fasyankes clustering.
     */
    public function mapData(Request $request)
    {
        return response()->json(
            $this->service->getMapData($request)
        );
    }

    /**
     * Export TBC Patient Data to Formatted Excel.
     */
    public function exportExcel(Request $request, \App\Modules\TBC\Services\ExportService $exportService)
    {
        $filePath = $exportService->exportExcel($request);
        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    /**
     * Display Executive SPM Dinkes Report for TBC (Print-friendly).
     */
    public function executiveReport(Request $request)
    {
        $stats = $this->service->getStatsData($request);
        return view('tbc.executive-report', array_merge($stats, [
            'selectedKabupaten'  => $request->input('kabupaten', ''),
            'selectedKelurahan'  => $request->input('kelurahan', ''),
            'selectedType'       => $request->input('report_type', ''),
        ]));
    }

    /**
     * AI: Parse free-text prompt into structured TBC patient fields.
     * Coerces types so the output always matches StorePatientRequest validation.
     */
    public function aiParsePatient(\Illuminate\Http\Request $request, \App\Services\GeminiAiService $ai)
    {
        $request->validate(['prompt' => 'required|string|min:10|max:2000']);

        $result = $ai->parseTbPatientFromPrompt($request->input('prompt'));

        if (!$result['success']) {
            return response()->json($result);
        }

        $data = $result['data'] ?? [];

        // ── Normalise integer fields ──────────────────────────────────────────
        if (isset($data['umur']) && $data['umur'] !== null && $data['umur'] !== '') {
            $data['umur'] = (int) $data['umur'];
        }

        // ── Normalise report_type ─────────────────────────────────────────────
        if (isset($data['report_type'])) {
            $rt = strtolower(trim((string) $data['report_type']));
            if (str_contains($rt, '06') || str_contains($rt, 'terduga')) {
                $data['report_type'] = 'tb_06';
            } else {
                $data['report_type'] = 'tb_03';
            }
        }

        // ── Strip to only allowed fields ──────────────────────────────────────
        $allowed = [
            'report_type','fasyankes_name','no_reg_sitb','no_reg_terduga','no_reg_pasien',
            'nik','nama_lengkap','umur','jenis_kelamin','pekerjaan',
            'provinsi','kabupaten','kecamatan','kelurahan','alamat_lengkap',
            'bulan','tanggal_daftar','tanggal_mulai_pengobatan','status_pengobatan',
            'tipe_diagnosis','lokasi_anatomi','riwayat_pengobatan','status_hiv','riwayat_dm',
            'hasil_tcm','hasil_mikroskopis','hasil_diagnosis','hasil_akhir_pengobatan',
        ];
        $data = array_intersect_key($data, array_flip($allowed));

        return response()->json(['success' => true, 'data' => $data]);
    }

    /**
     * API: AI Triage with Google Gemini for a TBC patient.
     */
    public function aiTriage(Request $request, \App\Modules\TBC\Models\TbPatient $patient, \App\Services\GeminiAiService $aiService)
    {
        $result = $aiService->triageTbPatient($patient->toArray());
        return response()->json($result);
    }

    /**
     * API: Treatment journey timeline for a TBC patient.
     */
    public function patientTimeline(\App\Modules\TBC\Models\TbPatient $patient)
    {
        $data = \App\Services\PatientTrackingService::getTbPatientTimeline($patient);
        return response()->json($data);
    }

    /**
     * API: Duplicate screening for a TBC patient.
     */
    public function checkDuplicates(\App\Modules\TBC\Models\TbPatient $patient)
    {
        $duplicates = \App\Services\PatientTrackingService::checkTbDuplicates($patient);
        return response()->json([
            'count'      => count($duplicates),
            'duplicates' => $duplicates,
        ]);
    }
}
