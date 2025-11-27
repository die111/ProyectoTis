<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\EtapasController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ClasificadosController;
use App\Http\Controllers\NotificationController;



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
    
    // Ruta de autenticación para broadcasting (Reverb/Echo)
    Broadcast::routes();
    
    // Dashboard principal para todos los usuarios autenticados
    Route::get('/dashboard/main', [DashboardController::class, 'index'])->name('dashboard');

    // Rutas de notificaciones - Vistas
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}', [NotificationController::class, 'show'])->name('notifications.show');
    
    // Rutas de notificaciones - API
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

        $user->unreadNotifications()->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    });

    // Ruta de prueba para notificaciones en tiempo real
    Route::get('/test-notification', function() {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if (! $user) {
            return 'No autenticado';
        }

        Log::info('Enviando notificación de prueba', [
            'user_id' => $user->id,
            'channel' => 'App.Models.User.' . $user->id
        ]);

        $notification = new \App\Notifications\FrontNotification(
            'Prueba de Notificación en Tiempo Real',
            'Esta es una notificación de prueba enviada via Reverb. Si la ves, ¡funciona! 🎉',
            'success',
            route('dashboard')
        );

        $user->notify($notification);

        Log::info('Notificación enviada', [
            'user_id' => $user->id,
            'notification_id' => $notification->id ?? 'N/A'
        ]);

        return 'Notificación enviada al usuario ' . $user->name . ' (ID: ' . $user->id . ')' .
               '<br><br>Revisa:<br>- La consola del navegador (F12)<br>- El terminal de Reverb<br>- El log: storage/logs/laravel.log';
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
    
    Route::post('admin/evaluacion/{competicion}/fase/{fase}/grupo/evaluar', [App\Http\Controllers\Admin\CalificacionGrupalController::class, 'evaluarGrupo'])->name('admin.evaluacion.evaluar-grupo');
});