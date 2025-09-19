<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:responsable_area'])->prefix('responsable')->name('responsable.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    
    // Rutas futuras (descomentar cuando estén listas)
    // Route::get('/area', [App\Http\Controllers\ResponsableController::class, 'area'])->name('area');
    // Route::get('/evaluadores', [App\Http\Controllers\ResponsableController::class, 'evaluadores'])->name('evaluadores');
});