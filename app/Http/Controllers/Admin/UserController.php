<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,responsable_area,evaluador,coordinador',
            'area' => 'nullable|string|max:255',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        
        User::create($validated);

        return redirect()->route('admin.users.index')
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

        return redirect()->route('admin.users.index')
                         ->with('success', 'Usuario actualizado exitosamente');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')
                             ->with('error', 'No puedes eliminar tu propio usuario');
        }
        
        $user->delete();

        return redirect()->route('admin.users.index')
                         ->with('success', 'Usuario eliminado exitosamente');
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

        return redirect()->route('admin.users.index')
                         ->with('success', 'Usuario restaurado exitosamente');
    }

    public function forceDelete($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.trashed')
                             ->with('error', 'No puedes eliminar permanentemente tu propio usuario');
        }
        
        $user->forceDelete();

        return redirect()->route('admin.users.trashed')
                         ->with('success', 'Usuario eliminado permanentemente');
    }
}