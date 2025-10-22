<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Role;
use App\Models\User;
use App\Models\Area;
use App\Models\Phase;

class PhaseFeatureTest extends TestCase
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
    public function admin_can_create_phase_via_http()
    {
        $response = $this->withoutMiddleware()
            ->post('/dashboard/admin/etapas', [
                'name' => 'Inscripción',
                'description' => 'Fase de inscripción',
                'clasificados' => 10,
                'is_active' => true,
            ]);
        $response->assertStatus(302);
        $this->assertDatabaseHas('phases', [
            'name' => 'Inscripción',
        ]);
    }

    /** @test */
    public function admin_can_view_phase_list()
    {
        Phase::create([
            'name' => 'Evaluación',
            'description' => 'Fase de evaluación',
            'is_active' => true,
        ]);
        $response = $this->get('/dashboard/admin/etapas');
        $response->assertStatus(200);
        $response->assertSee('Evaluación');
    }
}
