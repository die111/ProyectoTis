<?php
namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Role;
use App\Models\User;
use App\Models\Area;

class RoleSystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $area = Area::create([
            'name' => 'General',
            'description' => 'Área general',
        ]);
        $adminRole = Role::create([
            'name' => 'admin',
            'description' => 'Administrador',
        ]);
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role_id' => $adminRole->id,
            'area_id' => $area->id,
            'is_active' => true,
            'user_code' => 'ADM001',
        ]);
        $this->be(User::find($admin->id));
    }

    /** @test */
    public function full_role_flow_admin()
    {
        $this->withoutMiddleware();
        // Crear rol
        $response = $this->post('/dashboard/admin/roles', [
            'name' => 'tester',
            'description' => 'Rol de pruebas',
            'is_active' => true,
        ]);
        $response->assertStatus(302);
        $this->assertDatabaseHas('roles', ['name' => 'tester']);

        // Editar rol
        $role = Role::where('name', 'tester')->first();
        $response = $this->put('/dashboard/admin/roles/' . $role->id, [
            'name' => 'tester_editado',
            'description' => 'Rol editado',
            'is_active' => true,
        ]);
        $response->assertStatus(302);
        $this->assertDatabaseHas('roles', ['name' => 'tester_editado']);

        // Desactivar rol
        $response = $this->post('/dashboard/admin/roles/' . $role->id . '/deactivate');
        $response->assertStatus(302);
        $role->refresh();
        $this->assertFalse($role->is_active);

        // Activar rol
        $response = $this->post('/dashboard/admin/roles/' . $role->id . '/activate');
        $response->assertStatus(302);
        $role->refresh();
        $this->assertTrue($role->is_active);
    }
}
