<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin'])->prefix('dashboard/admin')->name('admin.')->group(function () {
    // Ruta dashboard de admin 
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Rutas de usuarios
    Route::resource('users', UserController::class);

    // Rutas temporales (redirigen al dashboard)
    Route::get('areas', function () {
        return redirect()->route('admin.dashboard');
    })->name('areas.index');
    
    Route::get('olimpistas', function () {
        return redirect()->route('admin.dashboard');
    })->name('olimpistas.index');
    
    Route::get('reportes', function () {
        return redirect()->route('admin.dashboard');
    })->name('reportes.index');

    // Futuras rutas de áreas (descomentar cuando estén listas)
    // Route::resource('areas', App\Http\Controllers\Admin\AreaController::class);
});