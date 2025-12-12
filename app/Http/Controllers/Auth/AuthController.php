<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        // Sanitizar email
        $email = filter_var($credentials['email'], FILTER_SANITIZE_EMAIL);
        $credentials['email'] = $email;

        $user = User::where('email', $email)->first();

        if (!$user) {
            // Log intento fallido sin revelar si el usuario existe
            Log::warning('Intento de login con email no registrado', [
                'email' => $email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            
            return back()->withErrors([
                'email' => 'Las credenciales proporcionadas no son válidas.',
            ])->onlyInput('email');
        }

        if (!$user->is_active) {
            Log::warning('Intento de login con cuenta inactiva', [
                'user_id' => $user->id,
                'email' => $email,
                'ip' => $request->ip()
            ]);
            
            return back()->withErrors([
                'email' => 'Tu cuenta está inactiva. Contacta al administrador.',
            ])->onlyInput('email');
        }

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            // Log login exitoso
            Log::info('Login exitoso', [
                'user_id' => $user->id,
                'email' => $email,
                'ip' => $request->ip()
            ]);
            
            return redirect()->intended(route('dashboard'));
        }

        // Log intento fallido con contraseña incorrecta
        Log::warning('Intento de login con contraseña incorrecta', [
            'user_id' => $user->id,
            'email' => $email,
            'ip' => $request->ip()
        ]);

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no son válidas.',
        ])->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('welcome');
    }
}