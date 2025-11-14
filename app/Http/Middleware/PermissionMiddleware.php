<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class PermissionMiddleware
{
    public function handle($request, Closure $next, $permission)
    {
    /** @var \App\Models\User|null $user */
    $user = Auth::user();
        if ($user && $user->role && $user->role->permissions->pluck('name')->contains($permission)) {
            return $next($request);
        }
        abort(403, 'No tienes permiso para acceder a esta funcionalidad.');
    }
}
