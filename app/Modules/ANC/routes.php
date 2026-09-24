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
    Route::get('/api/kecamatan', [DashboardController::class, 'kecamatanList'])->name('kecamatan.list');
    Route::get('/api/map-data', [DashboardController::class, 'mapData'])->name('map.data');
    Route::get('/export/excel', [DashboardController::class, 'exportExcel'])->name('export.excel');
    Route::get('/report/executive', [DashboardController::class, 'executiveReport'])->name('report.executive');

    // AI Triage, Timeline & Duplicates (Read/Analysis)
    Route::get('/patients/{patient}/ai-triage', [DashboardController::class, 'aiTriage'])->name('patients.ai-triage');
    Route::get('/patients/{patient}/timeline', [DashboardController::class, 'patientTimeline'])->name('patients.timeline');
    Route::get('/patients/{patient}/duplicates', [DashboardController::class, 'checkDuplicates'])->name('patients.duplicates');
    Route::get('/patients/{patient}', [PatientController::class, 'show'])->name('patients.show');

    // Actions restricted to Admin (Create, Update, Delete, Import, AI Parse, Record Birth, Test Alert)
    Route::middleware('role:Admin')->group(function () {
        Route::post('/ai-parse', [DashboardController::class, 'aiParsePatient'])->name('ai.parse');
        Route::post('/import/preview', [ImportController::class, 'preview'])->name('import.preview');
        Route::post('/import/commit', [ImportController::class, 'commit'])->name('import.commit');
        Route::post('/patients', [PatientController::class, 'store'])->name('patients.store');
        Route::put('/patients/{patient}', [PatientController::class, 'update'])->name('patients.update');
        Route::delete('/patients/{patient}', [PatientController::class, 'destroy'])->name('patients.destroy');
        Route::post('/patients/{patient}/record-birth', [PatientController::class, 'recordBirth'])->name('patients.record-birth');
        Route::post('/test-birth-alert', [PatientController::class, 'testBirthAlert'])->name('test-birth-alert');
    });
});
