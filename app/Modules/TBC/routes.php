<?php

use App\Modules\TBC\Controllers\DashboardController;
use App\Modules\TBC\Controllers\ImportController;
use App\Modules\TBC\Controllers\PatientController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| TBC Module Routes
|--------------------------------------------------------------------------
| All routes for the TBC (Tuberculosis) dashboard module.
| Loaded from: app/Modules/TBC/routes.php
|--------------------------------------------------------------------------
*/

// Dashboard & Reports
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/tb/stats-json', [DashboardController::class, 'statsJson'])->name('tb.stats.json');
Route::get('/tb/kelurahan-list', [DashboardController::class, 'kelurahanList'])->name('tb.kelurahan.list');
Route::get('/tb/kecamatan-list', [DashboardController::class, 'kecamatanList'])->name('tb.kecamatan.list');
Route::get('/tb/map-data', [DashboardController::class, 'mapData'])->name('tb.map.data');
Route::get('/tb/export/excel', [DashboardController::class, 'exportExcel'])->name('tb.export.excel');
Route::get('/tb/report/executive', [DashboardController::class, 'executiveReport'])->name('tb.report.executive');

// AI Triage, Timeline & Duplicates
Route::get('/tb/patients/{patient}/ai-triage', [DashboardController::class, 'aiTriage'])->name('tb.patients.ai-triage');
Route::get('/tb/patients/{patient}/timeline', [DashboardController::class, 'patientTimeline'])->name('tb.patients.timeline');
Route::get('/tb/patients/{patient}/duplicates', [DashboardController::class, 'checkDuplicates'])->name('tb.patients.duplicates');

// Import
Route::post('/tb/import/preview', [ImportController::class, 'preview'])->name('tb.import.preview');
Route::post('/tb/import/commit', [ImportController::class, 'commit'])->name('tb.import.commit');

// Patients CRUD
Route::get('/tb/patients/{patient}', [PatientController::class, 'show'])->name('tb.patients.show');
Route::post('/tb/patients', [PatientController::class, 'store'])->name('tb.patients.store');
Route::put('/tb/patients/{patient}', [PatientController::class, 'update'])->name('tb.patients.update');
Route::delete('/tb/patients/{patient}', [PatientController::class, 'destroy'])->name('tb.patients.destroy');
