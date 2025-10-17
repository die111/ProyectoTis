<?php
namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Competicion;
use App\Models\Role;
use App\Models\User;
use App\Models\Area;

class CompeticionFeatureTest extends TestCase
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
    public function admin_can_create_competicion_via_http()
    {
        $response = $this->post('/admin/competitions', [
            'name' => 'Olimpiada de Física',
            'description' => 'Competencia anual de física',
            'state' => 'activa',
            'fechaInicio' => '2025-11-01',
            'fechaFin' => '2025-11-05',
        ]);
        $response->assertStatus(302); // Redirección tras crear
        $this->assertDatabaseHas('competicions', [
            'name' => 'Olimpiada de Física',
            'state' => 'activa',
        ]);
    }

    /** @test */
    public function admin_can_view_competicion_list()
    {
        Competicion::create([
            'name' => 'Olimpiada de Química',
            'description' => 'Competencia anual de química',
            'state' => 'activa',
            'fechaInicio' => '2025-12-01',
            'fechaFin' => '2025-12-05',
        ]);
        $response = $this->get('/admin/competitions');
        $response->assertStatus(200);
        $response->assertSee('Olimpiada de Química');
    }
}
