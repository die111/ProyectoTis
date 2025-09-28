<?php

use App\Http\Controllers\Admin\AreaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CompeticionController;
use App\Http\Controllers\Admin\EtapaController;
use App\Http\Controllers\Admin\InscripcionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin'])->prefix('dashboard/admin')->name('admin.')->group(function () {
    // Ruta dashboard de admin 
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Rutas de usuarios
    // Route::resource('users', UserController::class);
    Route::resource('competicion', CompeticionController::class);
    Route::resource('roles', RoleController::class); 
    Route::resource('usuarios', UsuarioController::class);
    Route::resource('inscripcion', InscripcionController::class);
    Route::resource('etapas', EtapaController::class);
    Route::resource('areas', AreaController::class);
    Route::post('areas/bulk-activate', [AreaController::class, 'bulkActivate'])->name('areas.bulk-activate');
    Route::post('areas/bulk-deactivate', [AreaController::class, 'bulkDeactivate'])->name('areas.bulk-deactivate');
});