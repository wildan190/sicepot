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
        $desaList = $this->service->getDesaList();
        $yearList = $this->service->getYearList();

        $defaultTahun = !empty($yearList) ? (string) $yearList[0] : (string) date('Y');

        // Terapkan default bulan (01 = Januari) dan tahun ke request SEBELUM getStatsData,
        // agar baseQuery ikut terfilter saat reload tanpa query string.
        if (!$request->has('bulan')) {
            $request->merge(['bulan' => '01']);
        }
        if (!$request->has('tahun')) {
            $request->merge(['tahun' => $defaultTahun]);
        }

        $stats = $this->service->getStatsData($request);

        return view('stunting.dashboard', array_merge($stats, [
            'desaList'      => $desaList,
            'yearList'      => $yearList,
            'selectedDesa'  => $request->input('desa', ''),
            'selectedBulan' => $request->input('bulan'),
            'selectedTahun' => $request->input('tahun'),
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
     * Export Stunting Patient Data to Formatted Excel.
     */
    public function exportExcel(Request $request, \App\Modules\Stunting\Services\ExportService $exportService)
    {
        $filePath = $exportService->exportExcel($request);
        return response()->download($filePath)->deleteFileAfterSend(true);
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

    /**
     * Executive Report for Stunting program.
     */
    public function executiveReport(Request $request)
    {
        $stats    = $this->service->getStatsData($request);
        $desaList = $this->service->getDesaList();

        return view('stunting.executive-report', array_merge($stats, [
            'desaList'      => $desaList,
            'selectedDesa'  => $request->input('desa', ''),
            'selectedBulan' => $request->input('bulan', ''),
            'selectedTahun' => $request->input('tahun', ''),
        ]));
    }

    /**
     * AI: Parse free-text prompt into structured Stunting patient fields.
     */
    public function aiParsePatient(Request $request, \App\Services\GeminiAiService $ai)
    {
        $request->validate(['prompt' => 'required|string|min:10|max:2000']);

        $result = $ai->parseStuntingPatientFromPrompt($request->input('prompt'));

        if (!$result['success']) {
            return response()->json($result);
        }

        $data = $result['data'] ?? [];

        // Normalize numeric fields
        $numericFields = ['bb_lahir', 'tb_lahir', 'berat', 'tinggi', 'lila', 'bbu_zscore', 'tbu_zscore', 'bbtb_zscore'];
        foreach ($numericFields as $field) {
            if (isset($data[$field]) && $data[$field] !== null && $data[$field] !== '') {
                $data[$field] = (float) $data[$field];
            }
        }

        if (isset($data['usia_saat_ukur']) && $data['usia_saat_ukur'] !== null && $data['usia_saat_ukur'] !== '') {
            $data['usia_saat_ukur'] = (int) $data['usia_saat_ukur'];
        }

        $allowed = [
            'nik', 'nama', 'jenis_kelamin', 'tanggal_lahir', 'bb_lahir', 'tb_lahir',
            'nama_ortu', 'puskesmas', 'desa', 'posyandu', 'rt', 'rw', 'alamat',
            'usia_saat_ukur', 'tanggal_pengukuran', 'berat', 'tinggi', 'cara_ukur', 'lila',
            'bbu_kategori', 'bbu_zscore', 'tbu_kategori', 'tbu_zscore',
            'bbtb_kategori', 'bbtb_zscore', 'naik_berat_badan'
        ];
        $data = array_intersect_key($data, array_flip($allowed));

        return response()->json(['success' => true, 'data' => $data]);
    }
}
