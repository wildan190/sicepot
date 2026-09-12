<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AiDashboardController;
use App\Modules\TBC\Controllers\PatientFormController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// ── Public TB Skrining Form (no auth) ──────────────────────────────────────
Route::get('/tb/skrining', [PatientFormController::class, 'show'])->name('tb.form');
Route::post('/tb/skrining', [PatientFormController::class, 'store'])->name('tb.form.store');
Route::get('/tb/skrining/terima-kasih', [PatientFormController::class, 'success'])->name('tb.form.success');

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {
    // ── TBC Module ─────────────────────────────────────────────────────────
    require __DIR__ . '/../app/Modules/TBC/routes.php';

    // ── ANC (Ibu Hamil) Module ─────────────────────────────────────────────
    require __DIR__ . '/../app/Modules/ANC/routes.php';

    // ── Stunting (Balita Stunting) Module ──────────────────────────────────
    require __DIR__ . '/../app/Modules/Stunting/routes.php';

    // ── AI Dashboard Builder ────────────────────────────────────────────────
    Route::prefix('ai')->name('ai.')->group(function () {
        Route::get('/dashboard', [AiDashboardController::class, 'index'])->name('dashboard');
        Route::post('/dashboard/generate', [AiDashboardController::class, 'generate'])->name('dashboard.generate');
    });

    // ── User Profile ────────────────────────────────────────────────────────
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
