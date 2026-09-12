<?php

use App\Modules\Stunting\Controllers\DashboardController;
use App\Modules\Stunting\Controllers\ImportController;
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

    // Import Excel
    Route::post('/import/preview', [ImportController::class, 'preview'])->name('import.preview');
    Route::post('/import/commit', [ImportController::class, 'commit'])->name('import.commit');
});
