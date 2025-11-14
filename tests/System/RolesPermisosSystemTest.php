<?php

namespace Tests\System;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Area;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;

class RolesPermisosSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_flujo_permiso_protegido_por_middleware()
    {
        $role = Role::create(['name' => 'system-role-rp', 'description' => 'role']);
        $perm = Permission::create(['name' => 'acceso_system', 'description' => 'perm']);
        $role->permissions()->attach($perm->id);

        $area = Area::create(['name' => 'Area System RP', 'is_active' => true]);

        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'role_id' => $role->id,
            'area_id' => $area->id,
            'user_code' => Str::random(10),
        ]);

        // Ruta temporal protegida
        Route::get('/system-protected', function () {
            return 'ok';
        })->middleware(['auth', 'permission:acceso_system']);

        $this->actingAs($user)
            ->get('/system-protected')
            ->assertStatus(200)
            ->assertSee('ok');

        // Usuario sin permiso debe fallar
        $role2 = Role::create(['name' => 'system-role-rp-2', 'description' => 'role2']);
        $area2 = Area::create(['name' => 'Area System RP 2', 'is_active' => true]);
        $user2 = User::factory()->create([
            'role_id' => $role2->id,
            'area_id' => $area2->id,
            'user_code' => Str::random(10),
        ]);

        $this->actingAs($user2)
            ->get('/system-protected')
            ->assertStatus(403);
    }
}
