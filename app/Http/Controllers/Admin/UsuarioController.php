<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Area;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $areas = Area::all();
        $roles = Role::where('is_active', true)->get();
        $usuarios_activos_count = User::where('is_active', true)->count();
        $usuarios_inactivos_count = User::where('is_active', false)->count();

        $role = $request->query('role');
        $role_id = $request->query('role_id');
        $q = $request->query('q');
        $area_id = $request->query('area');
        $query = User::query();
        
        if ($role === 'activos') {
            $query->where('is_active', true);
        } elseif ($role === 'inactivos') {
            $query->where('is_active', false);
        } else {
            // Por defecto mostrar todos los usuarios (activos e inactivos)
            // No aplicar filtro de is_active
        }
        
        if ($role_id) {
            $query->where('role_id', $role_id);
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
        $users = $query->with('role')->orderBy('id')->paginate(10)->withQueryString();

        return view('admin.usuarios.index', compact('areas', 'roles', 'usuarios_activos_count', 'usuarios_inactivos_count', 'users'));
    }

    public function create()
    {
        $areas = Area::all();
        $roles = Role::where('is_active', true)->get();
        return view('admin.usuarios.formulario-usuario', compact('areas', 'roles'));
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
        $roles = Role::where('is_active', true)->get();
        $return = $request->get('return', url()->previous());
        
        // Usar el formulario universal
        return view('admin.usuarios.formulario-usuario', compact('user', 'areas', 'roles', 'return'));
    }

    public function update(Request $request, $id)
    {
        $user = User::withTrashed()->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'last_name_father' => 'required|string|max:255',
            'last_name_mother' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
            'role_id' => 'required|integer|exists:roles,id',
            'area_id' => 'required|integer|exists:areas,id',
            'user_code' => 'required|string|max:255|unique:users,user_code,' . $id,
            'level' => 'required|string|in:Primaria,Secundaria,Preuniversitario',
            'ci' => 'required|string|max:20|unique:users,ci,' . $id,
            'address' => 'nullable|string|max:255',
            'telephone_number' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        // Obtener la URL de retorno
        $returnUrl = $request->input('return', route('admin.usuarios.index'));
        
        return redirect($returnUrl)
            ->with('success', 'Usuario actualizado exitosamente');
    }

    public function destroy(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return redirect()->back()
                ->with('error', 'No puedes desactivar o activar tu propio usuario');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $accion = $user->is_active ? 'activado' : 'desactivado';
        
        // Mantener todos los parámetros de la URL actual
        return redirect()->back()
            ->with('success', "Usuario $accion exitosamente");
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
