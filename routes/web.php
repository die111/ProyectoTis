<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\EtapasController;

// Rutas públicas
Route::get('/', function () {
    return redirect()->route('welcome');
})->name('home');

Route::get('/welcome', [WelcomeController::class, 'index'])->name('welcome');

Route::get('/etapas', [EtapasController::class, 'index'])
    ->name('etapas.index'); // lista de etapas de la competición activa

Route::get('/etapas/{etapa}', [EtapasController::class, 'show'])
    ->whereNumber('etapa')
    ->name('etapas.show'); // detalle simple (opcional por ahora)    
// Rutas de autenticación
require __DIR__.'/auth.php';

// Rutas protegidas
Route::middleware('auth')->group(function () {
    
    // Dashboard principal para todos los usuarios autenticados
    Route::get('/dashboard/main', [DashboardController::class, 'index'])->name('dashboard');
    
    // Cargar rutas por roles
    // Ruta de admin
    require __DIR__.'/admin/routes.php';

    // require __DIR__.'/responsable/routes.php';
    // require __DIR__.'/evaluador/routes.php';
    // require __DIR__.'/coordinador/routes.php';
    
    // Rutas compartidas
    require __DIR__.'/shared/profile.php';

    Route::get('/admin/etapas', [EtapasController::class, 'admin'])
        ->name('admin.etapas.index'); // para más adelante (CRUD, finalizar, etc.)
    
});