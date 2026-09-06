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
