<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

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

        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Las credenciales proporcionadas no son válidas.',
            ])->onlyInput('email');
        }

        if (!$user->is_active) {
            return back()->withErrors([
                'email' => 'Tu cuenta está inactiva. Contacta al administrador.',
            ])->onlyInput('email');
        }

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            return redirect()->intended(route('dashboard'));
        }

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