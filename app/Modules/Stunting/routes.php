<?php

use App\Modules\Stunting\Controllers\DashboardController;
use App\Modules\Stunting\Controllers\ImportController;
use App\Modules\Stunting\Controllers\PatientController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Stunting (Balita Stunting) Module Routes
|--------------------------------------------------------------------------
*/

Route::prefix('stunting')->name('stunting.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/api/stats', [DashboardController::class, 'statsJson'])->name('stats.json');

    // Export Excel
    Route::get('/export/excel', [DashboardController::class, 'exportExcel'])->name('export.excel');

    // Laporan Eksekutif
    Route::get('/report/executive', [DashboardController::class, 'executiveReport'])->name('report.executive');

    // AI Parse Free Text
    Route::post('/ai/parse', [DashboardController::class, 'aiParsePatient'])->name('ai.parse');

    // Import Excel (single + bulk)
    Route::post('/import/preview', [ImportController::class, 'preview'])->name('import.preview');
    Route::post('/import/commit', [ImportController::class, 'commit'])->name('import.commit');
    Route::post('/import/bulk', [ImportController::class, 'bulkImport'])->name('import.bulk');

    // Clear Data Masif
    Route::delete('/clear-massive', [DashboardController::class, 'clearMassive'])->name('clear-massive');

    // Patients CRUD
    Route::post('/patients', [PatientController::class, 'store'])->name('patients.store');
    Route::put('/patients/{patient}', [PatientController::class, 'update'])->name('patients.update');
    Route::delete('/patients/{patient}', [PatientController::class, 'destroy'])->name('patients.destroy');
});

