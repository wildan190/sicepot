<?php

use App\Modules\ANC\Controllers\DashboardController;
use App\Modules\ANC\Controllers\ImportController;
use App\Modules\ANC\Controllers\PatientController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| ANC (Ibu Hamil) Module Routes
|--------------------------------------------------------------------------
| Self-contained routing for the ANC module.
*/

Route::prefix('anc')->name('anc.')->group(function () {
    // Dashboard & Stats & Export
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/api/stats', [DashboardController::class, 'statsJson'])->name('stats.json');
    Route::get('/api/kelurahan', [DashboardController::class, 'kelurahanList'])->name('kelurahan.list');
    Route::get('/export/excel', [DashboardController::class, 'exportExcel'])->name('export.excel');
    Route::get('/report/executive', [DashboardController::class, 'executiveReport'])->name('report.executive');

    // AI Triage, Timeline & Duplicates
    Route::get('/patients/{patient}/ai-triage', [DashboardController::class, 'aiTriage'])->name('patients.ai-triage');
    Route::get('/patients/{patient}/timeline', [DashboardController::class, 'patientTimeline'])->name('patients.timeline');
    Route::get('/patients/{patient}/duplicates', [DashboardController::class, 'checkDuplicates'])->name('patients.duplicates');

    // Import Universal
    Route::post('/import/preview', [ImportController::class, 'preview'])->name('import.preview');
    Route::post('/import/commit', [ImportController::class, 'commit'])->name('import.commit');

    // CRUD Pasien Ibu Hamil
    Route::post('/patients', [PatientController::class, 'store'])->name('patients.store');
    Route::get('/patients/{patient}', [PatientController::class, 'show'])->name('patients.show');
    Route::put('/patients/{patient}', [PatientController::class, 'update'])->name('patients.update');
    Route::delete('/patients/{patient}', [PatientController::class, 'destroy'])->name('patients.destroy');

    // Realtime Birth Alert (PieSocket WebSocket)
    Route::post('/patients/{patient}/record-birth', [PatientController::class, 'recordBirth'])->name('patients.record-birth');
    Route::post('/test-birth-alert', [PatientController::class, 'testBirthAlert'])->name('test-birth-alert');
});
