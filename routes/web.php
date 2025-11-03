<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\EtapasController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ClasificadosController;



// Rutas públicas
Route::get('/', function () {
    return redirect()->route('welcome');
})->name('home');

Route::get('/welcome', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/contactos', [ContactController::class, 'index'])->name('contactos');
Route::get('/clasificados', [ClasificadosController::class, 'index'])
    ->name('clasificados.index');

Route::get('/etapas', [EtapasController::class, 'index'])
    ->name('etapas.index'); // lista de etapas de la competición activa

Route::get('/etapas/{etapa}', [EtapasController::class, 'show'])
    ->whereNumber('etapa')
    ->name('etapas.show'); // detalle simple (opcional por ahora)    
// Rutas de autenticación
require __DIR__.'/auth.php';

//Rutas publicas
require __DIR__.'/public/routes.php';

// Rutas protegidas
Route::middleware('auth')->group(function () {
    
    // Dashboard principal para todos los usuarios autenticados
    Route::get('/dashboard/main', [DashboardController::class, 'index'])->name('dashboard');

    // Rutas de notificaciones
    Route::post('/notifications/{id}/read', function(string $id) {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if (! $user) {
            return response()->json(['success' => false], 401);
        }

        /** @var \Illuminate\Notifications\DatabaseNotification|null $notification */
        $notification = $user->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    });

    Route::post('/notifications/mark-all-read', function() {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if (! $user) {
            return response()->json(['success' => false], 401);
        }

        /** @var \Illuminate\Notifications\DatabaseNotificationCollection $collection */
        $collection = $user->unreadNotifications ?? collect();
        if (method_exists($collection, 'markAsRead')) {
            $collection->markAsRead();
        } else {
            // Fallback: mark each notification individually
            foreach ($collection as $n) {
                try { $n->markAsRead(); } catch (\Throwable $e) { /* ignore */ }
            }
        }

        return response()->json(['success' => true]);
    });

    // Ruta de prueba para notificaciones (temporal)
    Route::get('/test-notification', function() {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if ($user) {
            // PHPDoc above helps Intelephense recognize the Notifiable trait methods
            $user->notify(new App\Notifications\FrontNotification(
                'Notificación de Prueba',
                'Esta es una notificación de prueba para verificar el sistema',
                'info',
                route('dashboard')
            ));
            return 'Notificación enviada a ' . $user->name;
        }

        return redirect()->route('login');
    });

    //route visualizar sin controlador
    Route::get('/admin/etapas', function () {
        return view('admin.etapas.index');
    });


    // Cargar rutas por roles
    // Ruta de admin
    require __DIR__.'/admin/routes.php';

    // Ruta de estudiante
    require __DIR__.'/estudiante/inscripcion.php';

    // require __DIR__.'/responsable/routes.php';
    // require __DIR__.'/evaluador/routes.php';
    // require __DIR__.'/coordinador/routes.php';
    
    // Rutas compartidas
    require __DIR__.'/shared/profile.php';

    Route::get('/admin/etapas', [EtapasController::class, 'admin'])
        ->name('admin.etapas.index'); // para más adelante (CRUD, finalizar, etc.)
    
});