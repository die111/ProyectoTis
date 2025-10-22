<?php
namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Role;
use App\Models\User;
use App\Models\Area;

class RoleFeatureTest extends TestCase
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
    public function admin_can_create_role_via_http()
    {
        $response = $this->withoutMiddleware()
            ->post('/dashboard/admin/roles', [
                'name' => 'evaluador',
                'description' => 'Usuario encargado de evaluar',
                'is_active' => true,
            ]);
        $response->assertStatus(302);
        $this->assertDatabaseHas('roles', [
            'name' => 'evaluador',
        ]);
    }

    /** @test */
    public function admin_can_view_role_list()
    {
        Role::create([
            'name' => 'coordinador',
            'description' => 'Coordinador de eventos',
            'is_active' => true,
        ]);
        $response = $this->get('/dashboard/admin/roles');
        $response->assertStatus(200);
        $response->assertSee('coordinador');
    }
}
