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
        $responsableRoleId = \App\Models\Role::where('name', 'responsable_area')->value('id');
        $evaluadorRoleId = \App\Models\Role::where('name', 'evaluador')->value('id');
        $encargados_count = User::where('role_id', $responsableRoleId)->where('is_active', true)->count();
        $evaluadores_count = User::where('role_id', $evaluadorRoleId)->where('is_active', true)->count();
        $usuarios_activos_count = User::where('is_active', true)->count();

        $role = $request->query('role');
        $q = $request->query('q');
        $area_id = $request->query('area');
        $query = User::query();
        if ($role === 'responsable_area') {
            $query->where('role_id', $responsableRoleId)->where('is_active', true);
        } elseif ($role === 'evaluador') {
            $query->where('role_id', $evaluadorRoleId)->where('is_active', true);
        } elseif ($role === 'activos') {
            $query->where('is_active', true);
        } elseif ($q || $area_id) {
            $query->where('is_active', true);
        } else {
            // Por defecto mostrar todos los usuarios activos
            $query->where('is_active', true);
        }
        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%$q%")
                    ->orWhere('last_name_father', 'like', "%$q%")
                    ->orWhere('last_name_mother', 'like', "%$q%")
                    ->orWhere('email', 'like', "%$q%")
                    ->orWhere('user_code', 'like', "%$q%")
                    ->orWhere('school', 'like', "%$q%")
                    ->orWhere('level', 'like', "%$q%")
                ;
            });
        }
        if ($area_id) {
            $query->where('area_id', $area_id);
        }
        // Paginación: 10 por página, conservando parámetros de query
        $users = $query->with('role')->paginate(10)->withQueryString();

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
            'last_name_mother' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role_id' => 'required|integer|exists:roles,id',
            'area_id' => 'required|integer|exists:areas,id',
            'user_code' => 'required|string|max:255|unique:users,user_code',
            'level' => 'required|string|in:Primaria,Secundaria,Preuniversitario',
            'ci' => 'required|string|max:20|unique:users,ci',
            'address' => 'nullable|string|max:255',
            'telephone_number' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        $validated['is_active'] = true;

        User::create($validated);

        return redirect()->route('admin.usuarios.index')
            ->with([
                'swal_custom' => true,
                'swal_title' => '¡Éxito!',
                'swal_icon' => 'success',
                'swal_text' => 'Usuario creado exitosamente.'
            ]);
    }

    public function show($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        return view('users.show', compact('user'));
    }

    public function edit($id, Request $request)
    {
        $user = User::withTrashed()->findOrFail($id);
        $areas = Area::all();
        $return = $request->get('return', url()->previous());
        // Corrección: acceder al nombre del rol
        if ($user->role && $user->role->name === 'evaluador') {
            return view('admin.usuarios.edit-evaluador', compact('user', 'areas', 'return'));
        } elseif ($user->role && $user->role->name === 'responsable_area') {
            return view('admin.usuarios.edit-encargado', compact('user', 'areas', 'return'));
        } else {
            return redirect()->route('admin.usuarios.index')->with('error', 'Rol no soportado para edición personalizada');
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::withTrashed()->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            // password opcional en edición
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:admin,responsable_area,evaluador,coordinador',
            'area_id' => 'nullable|integer|exists:areas,id',
            'is_active' => 'boolean',
            'last_name_father' => 'required|string|max:255',
            'last_name_mother' => 'nullable|string|max:255',
            'user_code' => 'required|string|max:255',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        $return = $request->input('return');
        if ($return) {
            return redirect()->to($return)->with('success', 'Usuario actualizado exitosamente');
        }
        return redirect()->route('admin.usuarios.index', ['role' => $user->role])
            ->with('success', 'Usuario actualizado exitosamente')
            ->with('role', $user->role);
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

        return redirect()->route('admin.usuarios.index', ['role' => $user->role])
            ->with('success', 'Usuario desactivado exitosamente')
            ->with('role', $user->role);
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
