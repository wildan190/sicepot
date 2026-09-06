<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {
    // ── TBC Module ─────────────────────────────────────────────────────────
    require __DIR__ . '/../app/Modules/TBC/routes.php';

    // ── ANC (Ibu Hamil) Module ─────────────────────────────────────────────
    require __DIR__ . '/../app/Modules/ANC/routes.php';

    // ── User Profile ────────────────────────────────────────────────────────
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
