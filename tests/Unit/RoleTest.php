<?php
namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Role;
use App\Models\User;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected function setUp(): void
    {
        // Simular usuario admin autenticado
        parent::setUp();
        $adminRole = Role::create([
            'name' => 'admin',
            'description' => 'Administrador',
        ]);
        $area = \App\Models\Area::create([
            'name' => 'General',
            'description' => 'Área general',
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
    public function crear_rol()
    {
        $role = Role::create([
            'name' => 'evaluador',
            'description' => 'Usuario encargado de evaluar',
        ]);
        $this->assertDatabaseHas('roles', [
            'name' => 'evaluador',
        ]);
    }

    /** @test */
    public function desactivar_rol()
    {
        $role = Role::create([
            'name' => 'coordinador',
            'description' => 'Coordinador de eventos',
            'is_active' => false,
        ]);
        $role->is_active = true;
        $role->save();
        $this->assertTrue($role->fresh()->is_active);
        $role->is_active = false;
        $role->save();
        $this->assertFalse($role->fresh()->is_active);
    }
}
