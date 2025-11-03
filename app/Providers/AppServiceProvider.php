<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
        // Force Carbon locale to Spanish so diffForHumans() prints in Spanish
        try {
            Carbon::setLocale('es');
            // also ensure the application locale is set for translations
            app()->setLocale('es');
        } catch (\Throwable $e) {
            // if Carbon or locale setting fails, continue silently
        }
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
    }
}
