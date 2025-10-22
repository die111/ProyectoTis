<?php
namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Phase;
use App\Models\User;
use App\Models\Area;
use App\Models\Role;

class PhaseSystemTest extends TestCase
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
    public function full_phase_flow_admin()
    {
        $this->withoutMiddleware();
        // Crear fase
        $response = $this->post('/dashboard/admin/etapas', [
            'name' => 'Fase 1',
            'description' => 'Primera fase',
            'clasificados' => 5,
            'is_active' => true,
        ]);
        $response->assertStatus(302);
        $this->assertDatabaseHas('phases', ['name' => 'Fase 1']);

        // Editar fase
        $phase = Phase::where('name', 'Fase 1')->first();
        $response = $this->put('/dashboard/admin/etapas/' . $phase->id, [
            'name' => 'Fase 1 Editada',
            'description' => 'Fase editada',
            'clasificados' => 10,
            'is_active' => true,
        ]);
        $response->assertStatus(302);
        $this->assertDatabaseHas('phases', ['name' => 'Fase 1 Editada']);
    }
}
