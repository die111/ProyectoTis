<?php

use App\Http\Controllers\Admin\AreaController;
use App\Http\Controllers\DashboardController;
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
    Route::resource('usuarios', UsuarioController::class);
    // Formulario independiente para crear encargado de área
    Route::get('formulario-encargado', function() {
        $areas = \App\Models\Area::all();
        return view('admin.usuarios.formulario-encargado', compact('areas'));
    })->name('formulario-encargado');
    // Formulario independiente para crear evaluador
    Route::get('formulario-evaluador', function() {
        $areas = \App\Models\Area::where('is_active', true)->get();
        return view('admin.usuarios.formulario-evaluador', compact('areas'));
    })->name('formulario-evaluador');


    Route::resource('competicion', CompeticionController::class);
    Route::patch('competicion/{id}/estado/{state}', [CompeticionController::class, 'updateState'])->name('competicion.updateState');
    Route::get('competicion/{id}/json', [CompeticionController::class, 'json'])->name('competicion.json');


    // Rutas de roles
    Route::resource('roles', RoleController::class);
    Route::post('roles/{id}/activate', [RoleController::class, 'activate'])->name('roles.activate');
    Route::post('roles/{id}/deactivate', [RoleController::class, 'deactivate'])->name('roles.deactivate');

    // Rutas areas
    Route::resource('areas', AreaController::class);
    Route::post('areas/bulk-activate', [AreaController::class, 'bulkActivate'])->name('areas.bulk-activate');
    Route::post('areas/bulk-deactivate', [AreaController::class, 'bulkDeactivate'])->name('areas.bulk-deactivate');
    
    Route::resource('inscripcion', InscripcionController::class);
    Route::resource('etapas', EtapaController::class);
    Route::resource('areas', AreaController::class);

    // Ruta para guardar estudiantes
    Route::post('inscripcion/guardar-estudiantes', [InscripcionController::class, 'guardarEstudiantes'])->name('inscripcion.guardarEstudiantes');

    // Futuras rutas de áreas (descomentar cuando estén listas)
    // Route::resource('areas', App\Http\Controllers\Admin\AreaController::class);
});
