<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $areas = Area::all();
        $encargados_count = User::where('role', 'responsable_area')->where('is_active', true)->count();
        $evaluadores_count = User::where('role', 'evaluador')->where('is_active', true)->count();
        $usuarios_activos_count = User::where('is_active', true)->count();

        $role = $request->query('role');
        if ($role === 'responsable_area') {
            $users = User::where('role', 'responsable_area')->where('is_active', true)->select('id', 'name', 'last_name_father', 'last_name_mother', 'email')->get();
        } elseif ($role === 'evaluador') {
            $users = User::where('role', 'evaluador')->where('is_active', true)->select('id', 'name', 'last_name_father', 'last_name_mother', 'email')->get();
        } elseif ($role === 'activos') {
            $users = User::where('is_active', true)->select('id', 'name', 'last_name_father', 'last_name_mother', 'email')->get();
        } else {
            $users = [];
        }

        return view('admin.usuarios.index', compact('areas', 'encargados_count', 'evaluadores_count', 'usuarios_activos_count', 'users'));
    }

    public function create()
    {
        $areas = Area::all();
        return view('components.modals.formulario-encargado', compact('areas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'last_name_father' => 'required|string|max:255',
            'last_name_mother' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'role' => 'required|in:admin,responsable_area,evaluador,coordinador',
            'area_id' => 'required|string|max:255',
            'user_code' => 'required|string|max:255|unique:users,user_code',            
        ]);

        $validated['password'] = bcrypt($validated['password']  );

        User::create($validated);

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario creado exitosamente');
    }

    public function show($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        return view('users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::withTrashed()->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,responsable_area,evaluador,coordinador',
            'area' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario actualizado exitosamente');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return redirect()->route('admin.usuarios.index')
                ->with('error', 'No puedes desactivar tu propio usuario');
        }

        $user->is_active = false;
        $user->save();

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario desactivado exitosamente');
    }

    // MÉTODOS SOFT DELETE

    public function trashed()
    {
        $users = User::onlyTrashed()->get();
        return view('users.trashed', compact('users'));
    }

    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario restaurado exitosamente');
    }

    public function forceDelete($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);

        if ($user->id === Auth::id()) {
            return redirect()->route('admin.usuarios.trashed')
                ->with('error', 'No puedes eliminar permanentemente tu propio usuario');
        }

        $user->forceDelete();

        return redirect()->route('admin.usuarios.trashed')
            ->with('success', 'Usuario eliminado permanentemente');
    }
}
