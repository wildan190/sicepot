<?php

namespace App\Modules\ANC\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\ANC\Requests\ImportCommitRequest;
use App\Modules\ANC\Requests\ImportPreviewRequest;
use App\Modules\ANC\Services\ImportService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportController extends Controller
{
    public function __construct(protected ImportService $service) {}

    /**
     * Parse and preview an uploaded Excel/CSV file before confirmation.
     */
    public function preview(ImportPreviewRequest $request)
    {
        $file      = $request->file('file');
        $filename  = 'anc_import_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $stored    = $file->storeAs('temp_imports', $filename, 'local');
        $fullPath  = Storage::disk('local')->path($stored);

        try {
            $preview                  = $this->service->previewFile($fullPath);
            $preview['temp_token']    = $stored;
            $preview['original_name'] = $file->getClientOriginalName();

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
    public function commit(ImportCommitRequest $request)
    {
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
                'message' => "Berhasil mengimpor {$result['total']} data ibu hamil ({$result['inserted']} baru, {$result['updated']} diperbarui).",
                'result'  => $result,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses import: ' . $e->getMessage(),
            ], 500);
        }
    }
}
