<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use App\Models\Area;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;

class RolesPermisosFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_acceso_ruta_protegida_por_permiso()
    {
        $role = Role::create(['name' => 'feature-role', 'description' => 'role feature']);
        $perm = Permission::create(['name' => 'ver_panel_ft', 'description' => 'perm feature']);
        $role->permissions()->attach($perm->id);

        $area = Area::create(['name' => 'Area FT', 'is_active' => true]);

        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'role_id' => $role->id,
            'area_id' => $area->id,
            'user_code' => Str::random(10),
        ]);

        // Definir ruta temporal protegida por middleware 'permission'
        Route::get('/ft-protected', function () {
            return 'ok';
        })->middleware(['auth', 'permission:ver_panel_ft']);

        $this->actingAs($user)
            ->get('/ft-protected')
            ->assertStatus(200)
            ->assertSee('ok');
    }
}
