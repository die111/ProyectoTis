<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'last_name_father' => 'nullable|string|max:255',
            'last_name_mother' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'ci' => 'required|string|max:255|unique:users,ci',
            'user_code' => 'nullable|string|unique:users,user_code',
        ]);

        $role = Role::whereIn('name', ['olimpista', 'estudiante'])->first();

        if (!$role) {
            $role = Role::create(['name' => 'olimpista', 'description' => 'Estudiante (olimpista)']);
        }

        $userCode = $data['user_code'] ?? null;
        if (empty($userCode)) {
            do {
                // Genera un código legible: 'U' + 8 caracteres alfanum
                $userCode = 'U' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
            } while (User::where('user_code', $userCode)->exists());
        }

        $user = User::create([
            'name' => $data['name'],
            'last_name_father' => $data['last_name_father'] ?? null,
            'last_name_mother' => $data['last_name_mother'] ?? null,
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $role->id,
            'is_active' => true,
            'ci' => $data['ci'],
            'area_id' => $data['area_id'] ?? null,
            'user_code' => $userCode,
        ]);

        // Logear al usuario recién creado
        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
