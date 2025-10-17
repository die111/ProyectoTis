<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// Rutas públicas
Route::get('/contactos', [HomeController::class, 'contactos'])->name('contactos');
Route::get('/clasificados', [HomeController::class, 'clasificados'])
    ->name('clasificados.index');

Route::get('/etapas', [HomeController::class, 'etapas'])
    ->name('etapas.index'); 

Route::get('/etapas/{etapa}', [HomeController::class, 'show'])
    ->whereNumber('etapa')
    ->name('etapas.show');