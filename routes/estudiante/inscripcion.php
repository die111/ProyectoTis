<?php

use App\Http\Controllers\Estudiante\InscripcionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'permission:inscripcion_competencia'])->group(function () {
    Route::get('/inscripcion', [InscripcionController::class, 'index'])->name('estudiante.inscripcion.index');
    Route::get('/inscripcion/crear/{competicion}', [InscripcionController::class, 'create'])->name('estudiante.inscripcion.create');
    Route::get('/inscripcion/competencias-activas', [InscripcionController::class, 'competenciasActivas'])->name('estudiante.inscripcion.competencias');
    Route::post('/inscripcion/{competicion}', [InscripcionController::class, 'inscribir'])->name('estudiante.inscripcion.inscribir');
    Route::get('/inscripcion/mis-inscripciones', [InscripcionController::class, 'misInscripciones'])->name('estudiante.inscripcion.mis-inscripciones');
});
