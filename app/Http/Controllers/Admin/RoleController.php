<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('search');
        $roles = Role::query();
        if ($query) {
            $search = mb_strtolower($query);
            $roles->whereRaw('LOWER(name) LIKE ?', ["%$search%"])
                  ->orWhereRaw('LOWER(description) LIKE ?', ["%$search%"]);
        }
        $roles = $roles->orderBy('id')->paginate(10);
        return view('admin.roles.index', compact('roles', 'query'));
    }

    public function create()
    {
        $permissions = \App\Models\Permission::all();
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:20|unique:roles,name',
            'description' => 'nullable|string|max:30',
            'permissions' => 'array',
        ]);
        $role = Role::create($request->only('name', 'description'));
        $role->permissions()->sync($request->input('permissions', []));
        return redirect()->route('admin.roles.index')
        ->with([
            'swal_custom' => true,
            'swal_title' => '¡Éxito!',
            'swal_icon' => 'success',
            'swal_text' => 'Rol creado correctamente.'
        ]);
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $permissions = \App\Models\Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:20|unique:roles,name,' . $role->id,
            'description' => 'nullable|string|max:30',
            'permissions' => 'array',
        ]);
        $role->update($request->only('name', 'description'));
        $role->permissions()->sync($request->input('permissions', []));
        return redirect()->route('admin.roles.index')
        ->with([
            'swal_custom' => true,
            'swal_title' => '¡Éxito!',
            'swal_icon' => 'success',
            'swal_text' => 'Rol actualizado correctamente.'
        ]);
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return redirect()->route('admin.roles.index')
        ->with([
            'swal_custom' => true,
            'swal_title' => '¡Éxito!',
            'swal_icon' => 'success',
            'swal_text' => 'Rol deshabilitado correctamente.'
        ]);
    }

    public function activate($id)
    {
        $role = Role::findOrFail($id);
        $role->is_active = true;
        $role->save();
        return redirect()->route('admin.roles.index')
        ->with([
            'swal_custom' => true,
            'swal_title' => '¡Éxito!',
            'swal_icon' => 'success',
            'swal_text' => 'Rol activado correctamente.'
        ]);
    }

    public function deactivate($id)
    {
        $role = Role::findOrFail($id);
        $role->is_active = false;
        $role->save();
        return redirect()->route('admin.roles.index')
        ->with([
            'swal_custom' => true,
            'swal_title' => '¡Éxito!',
            'swal_icon' => 'success',
            'swal_text' => 'Rol desactivado correctamente.'
        ]);
    }
}
