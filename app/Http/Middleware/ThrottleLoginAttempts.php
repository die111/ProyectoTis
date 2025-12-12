<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ThrottleLoginAttempts
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $key = $this->throttleKey($request);
        
        // Máximo 5 intentos en 1 minuto
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            
            return back()->withErrors([
                'email' => "Demasiados intentos de inicio de sesión. Por favor, intenta de nuevo en {$seconds} segundos."
            ])->withInput($request->only('email'));
        }
        
        // Incrementar el contador de intentos
        RateLimiter::hit($key, 60); // 60 segundos de bloqueo
        
        $response = $next($request);
        
        // Si el login fue exitoso (verificar redirección)
        if ($response->isRedirect() && Auth::check()) {
            RateLimiter::clear($key);
        }
        
        return $response;
    }
    
    /**
     * Get the throttle key for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function throttleKey(Request $request): string
    {
        return Str::lower($request->input('email')).'|'.$request->ip();
    }
}
