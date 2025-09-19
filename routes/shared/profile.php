<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    // Rutas de perfil de usuario
    // Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    // Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});