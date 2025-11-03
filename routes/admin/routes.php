<?php

use App\Http\Controllers\Admin\AreaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\CompeticionController;
use App\Http\Controllers\Admin\EtapaController;
use App\Http\Controllers\Admin\InscripcionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Admin\EvaluacionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('dashboard/admin')->name('admin.')->group(function () {
    // Ruta dashboard de admin 
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Rutas de usuarios
    Route::resource('usuarios', UsuarioController::class); // ->middleware('permission:usuarios')
    // Formulario independiente para crear usuario
    Route::get('formulario-usuario', function() {
        $areas = \App\Models\Area::all();
        $roles = \App\Models\Role::where('is_active', true)->get();
        return view('admin.usuarios.formulario-usuario', compact('areas', 'roles'));
    })->name('formulario-usuario');


    Route::resource('competicion', CompeticionController::class); // ->middleware('permission:competicion')
    Route::patch('competicion/{id}/estado/{state}', [CompeticionController::class, 'updateState'])->name('competicion.updateState'); // ->middleware('permission:competicion')
    Route::get('competicion/{id}/json', [CompeticionController::class, 'json'])->name('competicion.json'); // ->middleware('permission:competicion')


    // Rutas de roles
    Route::resource('roles', RoleController::class); // ->middleware('permission:roles')
    Route::post('roles/{id}/activate', [RoleController::class, 'activate'])->name('roles.activate'); // ->middleware('permission:roles')
    Route::post('roles/{id}/deactivate', [RoleController::class, 'deactivate'])->name('roles.deactivate'); // ->middleware('permission:roles')

    // Rutas areas
    Route::resource('areas', AreaController::class); // ->middleware('permission:areas')
    Route::post('areas/bulk-activate', [AreaController::class, 'bulkActivate'])->name('areas.bulk-activate'); // ->middleware('permission:areas')
    Route::post('areas/bulk-deactivate', [AreaController::class, 'bulkDeactivate'])->name('areas.bulk-deactivate'); // ->middleware('permission:areas')
    
        // Ruta para crear etapa (etapas.create)
        Route::get('etapas/create', [EtapaController::class, 'create'])->name('etapas.create');
    // Ruta para la página de solicitud de inscripción (debe ir ANTES del resource)
    Route::get('inscripcion/solicitud', [InscripcionController::class, 'solicitud'])->name('inscripcion.solicitud');
    Route::post('inscripcion/solicitud/{id}/estado', [InscripcionController::class, 'actualizarEstado'])->name('inscripcion.actualizarEstado');
    
    Route::resource('inscripcion', InscripcionController::class); // ->middleware('permission:inscripcion')
    Route::resource('phases', EtapaController::class)->names('phases'); // ->middleware('permission:fases')
    Route::patch('phases/{id}/habilitar', [EtapaController::class, 'habilitar'])->name('phases.habilitar'); // ->middleware('permission:fases')

    // Ruta para guardar estudiantes
    Route::post('inscripcion/guardar-estudiantes', [InscripcionController::class, 'guardarEstudiantes'])->name('inscripcion.guardarEstudiantes');

    // Ruta evaluaciones
    Route::resource('evaluacion', EvaluacionController::class); // ->middleware('permission:evaluaciones')

    // Futuras rutas de áreas (descomentar cuando estén listas)
    // Route::resource('areas', App\Http\Controllers\Admin\AreaController::class);
});
