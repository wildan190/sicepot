<?php

namespace App\Modules\Stunting\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Stunting\Services\ImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportController extends Controller
{
    public function __construct(protected ImportService $service) {}

    /**
     * Parse and preview an uploaded Excel file before confirmation.
     */
    public function preview(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:10240',
        ]);

        $file     = $request->file('file');
        $filename = 'stunting_import_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $stored   = $file->storeAs('temp_imports', $filename, 'local');
        $fullPath = Storage::disk('local')->path($stored);

        try {
            $preview                   = $this->service->previewFile($fullPath);
            $preview['temp_token']     = $stored;
            $preview['original_name']  = $file->getClientOriginalName();

            return response()->json(['success' => true, 'data' => $preview]);
        } catch (\Throwable $e) {
            Storage::disk('local')->delete($stored);
            return response()->json([
                'success' => false,
                'message' => 'Gagal membaca file: ' . $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Commit and import data from previewed file into the database.
     */
    public function commit(Request $request)
    {
        $request->validate(['temp_token' => 'required|string']);

        $tempPath = $request->input('temp_token');

        if (!Storage::disk('local')->exists($tempPath)) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi import kedaluwarsa atau file tidak ditemukan. Silakan upload ulang.',
            ], 404);
        }

        $fullPath = Storage::disk('local')->path($tempPath);

        try {
            $result = $this->service->importFile($fullPath);
            Storage::disk('local')->delete($tempPath);

            return response()->json([
                'success' => true,
                'message' => "Berhasil mengimpor {$result['total']} data balita stunting ({$result['inserted']} baru, {$result['updated']} diperbarui).",
                'result'  => $result,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses import: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Bulk import: process multiple Excel files in one request.
     * Accepts files[] array, imports each immediately, and returns a per-file summary.
     */
    public function bulkImport(Request $request)
    {
        $request->validate([
            'files'   => 'required|array|min:1|max:20',
            'files.*' => 'required|file|mimes:xlsx,xls|max:20480',
        ]);

        $results = [];
        $totalIn = 0;
        $totalUp = 0;

        foreach ($request->file('files') as $file) {
            $filename = 'stunting_bulk_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $stored   = $file->storeAs('temp_imports', $filename, 'local');
            $fullPath = Storage::disk('local')->path($stored);

            try {
                $result = $this->service->importFile($fullPath);
                Storage::disk('local')->delete($stored);

                $results[] = [
                    'file'     => $file->getClientOriginalName(),
                    'success'  => true,
                    'total'    => $result['total'],
                    'inserted' => $result['inserted'],
                    'updated'  => $result['updated'],
                    'message'  => "{$result['total']} data ({$result['inserted']} baru, {$result['updated']} diperbarui)",
                ];
                $totalIn += $result['inserted'];
                $totalUp += $result['updated'];
            } catch (\Throwable $e) {
                Storage::disk('local')->delete($stored);
                $results[] = [
                    'file'    => $file->getClientOriginalName(),
                    'success' => false,
                    'message' => 'Gagal: ' . $e->getMessage(),
                ];
            }
        }

        $totalFiles = count($results);
        $failCount  = count(array_filter($results, fn($r) => !$r['success']));

        return response()->json([
            'success'     => $failCount < $totalFiles,
            'message'     => "Selesai memproses {$totalFiles} file. Total " . ($totalIn + $totalUp) .
                             " data ({$totalIn} baru, {$totalUp} diperbarui)" .
                             ($failCount > 0 ? ", {$failCount} file gagal." : "."),
            'results'     => $results,
            'grand_total' => $totalIn + $totalUp,
            'inserted'    => $totalIn,
            'updated'     => $totalUp,
            'failed'      => $failCount,
        ]);
    }
}
