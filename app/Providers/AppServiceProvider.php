<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use App\Models\Role;
use App\Observers\RoleObserver;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Notifications\Events\BroadcastNotificationCreated;
use App\Listeners\AddNotificationIdToBroadcast;
use Carbon\Carbon;

// Eloquent event classes
use Illuminate\Database\Eloquent\Events\Created as EloquentCreated;
use Illuminate\Database\Eloquent\Events\Updated as EloquentUpdated;
use Illuminate\Database\Eloquent\Events\Deleted as EloquentDeleted;
use App\Services\AuditService;
use App\Models\Audit as AuditModel;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Forzar HTTPS en producción para evitar errores de contenido mixto
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
        
        // Force Carbon locale to Spanish so diffForHumans() prints in Spanish
        try {
            Carbon::setLocale('es');
            // also ensure the application locale is set for translations
            app()->setLocale('es');
        } catch (\Throwable $e) {
            // if Carbon or locale setting fails, continue silently
        }
        
        // Registrar listener para agregar ID de notificación al broadcast
        Event::listen(
            BroadcastNotificationCreated::class,
            AddNotificationIdToBroadcast::class
        );
        
        // Global audit listeners: record created/updated/deleted for all Eloquent models
        // Skip models that already use the Auditable trait to avoid duplicate entries,
        // and skip the Audit model itself.
        Event::listen('eloquent.created: *', function ($eventName, $data) {
            $model = $data[0] ?? null;
            if (!$model) return;
            if ($model instanceof AuditModel) return;
            $allUses = [];
            $cls = get_class($model);
            do {
                $allUses = array_merge($allUses, class_uses($cls) ?: []);
            } while ($cls = get_parent_class($cls));
            if (in_array(\App\Traits\Auditable::class, $allUses)) return;
            try {
                app(AuditService::class)->record($model, 'created');
            } catch (\Throwable $e) {
                Log::error('Error en auditoría (created): ' . $e->getMessage(), [
                    'model' => get_class($model),
                    'id' => $model->getKey(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        Event::listen('eloquent.updated: *', function ($eventName, $data) {
            $model = $data[0] ?? null;
            if (!$model) return;
            if ($model instanceof AuditModel) return;
            $allUses = [];
            $cls = get_class($model);
            do {
                $allUses = array_merge($allUses, class_uses($cls) ?: []);
            } while ($cls = get_parent_class($cls));
            if (in_array(\App\Traits\Auditable::class, $allUses)) return;
            try {
                app(AuditService::class)->record($model, 'updated');
            } catch (\Throwable $e) {
                Log::error('Error en auditoría (updated): ' . $e->getMessage(), [
                    'model' => get_class($model),
                    'id' => $model->getKey(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        Event::listen('eloquent.deleted: *', function ($eventName, $data) {
            $model = $data[0] ?? null;
            if (!$model) return;
            if ($model instanceof AuditModel) return;
            $allUses = [];
            $cls = get_class($model);
            do {
                $allUses = array_merge($allUses, class_uses($cls) ?: []);
            } while ($cls = get_parent_class($cls));
            if (in_array(\App\Traits\Auditable::class, $allUses)) return;
            try {
                app(AuditService::class)->record($model, 'deleted');
            } catch (\Throwable $e) {
                Log::error('Error en auditoría (deleted): ' . $e->getMessage(), [
                    'model' => get_class($model),
                    'id' => $model->getKey(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });
        
        // Compartir notificaciones no leídas con todas las vistas
        View::composer('*', function ($view) {
            // Guard against missing notifications table or other DB issues during tests
            // If anything goes wrong retrieving notifications, fall back to an empty collection.
            try {
                if (Auth::check()) {
                    /** @var \App\Models\User|null $user */
                    $user = Auth::user();
                    $view->with('sidebarNotifications', $user->unreadNotifications ?? collect());
                } else {
                    $view->with('sidebarNotifications', collect());
                }
            } catch (\Throwable $e) {
                $view->with('sidebarNotifications', collect());
            }
        });

        // Registrar observer para Role: evita eliminación si tiene usuarios y limpia caché
        Role::observe(RoleObserver::class);
    }
}
