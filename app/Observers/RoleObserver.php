<?php

namespace App\Observers;

use App\Models\Role;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class RoleObserver
{
    /**
     * Handle the Role "created" event.
     */
    public function created(Role $role): void
    {
        // Limpiar cache relacionado con permisos si existiera
        Cache::forget("role_permissions_{$role->id}");
        Log::info('Role creado y cache limpiado', ['role_id' => $role->id, 'name' => $role->name]);
    }

    /**
     * Handle the Role "updated" event.
     */
    public function updated(Role $role): void
    {
        Cache::forget("role_permissions_{$role->id}");
        Log::info('Role actualizado y cache limpiado', ['role_id' => $role->id]);
    }

    /**
     * Handle the Role "deleting" event.
     * Prevent deletion if there are users assigned to this role.
     */
    public function deleting(Role $role): bool
    {
        if ($role->users()->count() > 0) {
            // Throwing an exception will abort the delete and can be handled by the controller
            throw new \Exception('No se puede eliminar el rol porque tiene usuarios asignados.');
        }
        return true;
    }

    /**
     * Handle the Role "deleted" event.
     */
    public function deleted(Role $role): void
    {
        Cache::forget("role_permissions_{$role->id}");
        Log::info('Role eliminado y cache limpiado', ['role_id' => $role->id]);
    }
}
