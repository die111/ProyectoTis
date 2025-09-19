<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (!$user->is_active) {
            Auth::logout();
            return redirect()->route('login')
                ->withErrors(['email' => 'Tu cuenta está inactiva. Contacta al administrador.']);
        }

        if (!in_array($user->role, $roles)) {
            return match($user->role) {
                'admin' => redirect()->route('admin.dashboard'),
                'responsable_area' => redirect()->route('responsable.dashboard'),
                'evaluador' => redirect()->route('evaluador.dashboard'),
                'coordinador' => redirect()->route('coordinador.dashboard'),
                default => redirect()->route('dashboard')
                    ->with('error', 'No tienes permisos para acceder a esa sección.')
            };
        }

        return $next($request);
    }
}