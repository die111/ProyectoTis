<?php

use App\Http\Controllers\Admin\AreaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\CompeticionController;
use App\Http\Controllers\Admin\EtapaController;
use App\Http\Controllers\Admin\InscripcionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Admin\EvaluacionController;
use App\Http\Controllers\Admin\CategoriaController;
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
    
    // Rutas categorías
    Route::resource('categorias', CategoriaController::class); // ->middleware('permission:categorias')
    Route::patch('categorias/{categoria}/activate', [CategoriaController::class, 'activate'])->name('categorias.activate');
    Route::patch('categorias/{categoria}/deactivate', [CategoriaController::class, 'deactivate'])->name('categorias.deactivate');

        // Ruta para crear etapa (etapas.create)
        Route::get('etapas/create', [EtapaController::class, 'create'])->name('etapas.create');
    // Ruta para la página de solicitud de inscripción (debe ir ANTES del resource)
    Route::get('inscripcion/solicitud', [InscripcionController::class, 'solicitud'])->name('inscripcion.solicitud');
    Route::post('inscripcion/solicitud/{id}/estado', [InscripcionController::class, 'actualizarEstado'])->name('inscripcion.actualizarEstado');

    // Endpoints JSON de inscripción (deben ir antes del resource para evitar colisión con {inscripcion})
    Route::get('inscripcion/get-areas', [InscripcionController::class, 'getAreas'])->name('inscripcion.getAreas');
    Route::get('inscripcion/get-competiciones', [InscripcionController::class, 'getCompeticiones'])->name('inscripcion.getCompeticiones');
    
    Route::resource('inscripcion', InscripcionController::class); // ->middleware('permission:inscripcion')
    Route::resource('phases', EtapaController::class)->names('phases'); // ->middleware('permission:fases')
    Route::patch('phases/{id}/habilitar', [EtapaController::class, 'habilitar'])->name('phases.habilitar'); // ->middleware('permission:fases')

    // Rutas para inscripción
    Route::post('inscripcion/guardar-estudiantes', [InscripcionController::class, 'guardarEstudiantes'])->name('inscripcion.guardarEstudiantes');

    // Ruta evaluaciones
    Route::resource('evaluacion', EvaluacionController::class); // ->middleware('permission:evaluaciones')
    
    // Ruta para mostrar las fases de una competición específica
    Route::get('evaluacion/{competicion}/fases', [EvaluacionController::class, 'showFases'])->name('evaluacion.fases');
    
    // Ruta para gestionar estudiantes de una fase específica en una competición específica
    Route::get('evaluacion/{competicion}/fase/{fase}/estudiantes', [EvaluacionController::class, 'gestionarEstudiantes'])->name('evaluacion.fase.estudiantes');
    
    // Ruta para calificar estudiantes de una fase específica
    Route::get('evaluacion/{competicion}/fase/{fase}/calificar', [EvaluacionController::class, 'calificar'])->name('evaluacion.calificar');
    
    // Ruta para guardar calificaciones
    Route::post('evaluacion/{competicion}/fase/{fase}/calificar', [EvaluacionController::class, 'guardarCalificaciones'])->name('evaluacion.guardar-calificaciones');
    
    // Ruta para clasificar por cupo
    Route::post('evaluacion/{competicion}/fase/{fase}/clasificar-cupo', [EvaluacionController::class, 'clasificarPorCupo'])->name('evaluacion.clasificar-cupo');
    
    // Ruta para clasificar por notas altas
    Route::post('evaluacion/{competicion}/fase/{fase}/clasificar-notas', [EvaluacionController::class, 'clasificarPorNotasAltas'])->name('evaluacion.clasificar-notas');
    
    // Ruta para finalizar fase desde la vista de calificación
    Route::post('evaluacion/{competicion}/fase/{fase}/finalizar', [EvaluacionController::class, 'finalizarFase'])->name('evaluacion.finalizar-fase');

    // Futuras rutas de áreas (descomentar cuando estén listas)
    // Route::resource('areas', App\Http\Controllers\Admin\AreaController::class);
});
