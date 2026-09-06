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
