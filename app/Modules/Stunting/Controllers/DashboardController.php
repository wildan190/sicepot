<?php

namespace App\Modules\Stunting\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Stunting\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(protected DashboardService $service) {}

    /**
     * Display the Stunting Dashboard.
     */
    public function index(Request $request)
    {
        $stats    = $this->service->getStatsData($request);
        $desaList = $this->service->getDesaList();
        $yearList = $this->service->getYearList();

        return view('stunting.dashboard', array_merge($stats, [
            'desaList'    => $desaList,
            'yearList'    => $yearList,
            'selectedDesa'  => $request->input('desa', ''),
            'selectedBulan' => $request->input('bulan', ''),
            'selectedTahun' => $request->input('tahun', ''),
        ]));
    }

    /**
     * API: Filtered stats (JSON) for AJAX updates.
     */
    public function statsJson(Request $request)
    {
        return response()->json($this->service->getStatsData($request));
    }

    /**
     * Clear all stunting records massively from database.
     */
    public function clearMassive()
    {
        $deletedCount = \App\Modules\Stunting\Models\StuntingPatient::count();
        \App\Modules\Stunting\Models\StuntingPatient::truncate();

        return response()->json([
            'success' => true,
            'message' => "Semua data balita stunting ({$deletedCount} data) berhasil dikosongkan secara permanen.",
            'deleted_count' => $deletedCount,
        ]);
    }
}
