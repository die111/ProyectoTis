<?php
namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Phase;
use App\Models\Area;

use App\Models\User;

class PhaseTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Simular usuario admin autenticado
        $area = \App\Models\Area::create([
            'name' => 'General',
            'description' => 'Área general',
        ]);
        $adminRole = \App\Models\Role::create([
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
    public function crear_fase()
    {
        $phase = Phase::create([
            'name' => 'Inscripción',
            'description' => 'Fase de inscripción',
            'start_date' => '2025-10-01',
            'end_date' => '2025-10-10',
        ]);
        $this->assertDatabaseHas('phases', [
            'name' => 'Inscripción',
        ]);
    }

    /** @test */
    public function editar_fase()
    {
        $area = Area::create([
            'name' => 'Física',
            'description' => 'Área de física',
        ]);

        $phase = Phase::create([
            'area_id' => $area->id,
            'name' => 'Primera Ronda',
            'description' => 'Fase inicial',
            'start_date' => '2025-10-11',
            'end_date' => '2025-10-15',
        ]);

        $phase->update(['name' => 'Ronda 1']);
        $this->assertDatabaseHas('phases', ['name' => 'Ronda 1']);
    }

    /** @test */
    public function eliminar_fase()
    {
        $area = Area::create([
            'name' => 'Química',
            'description' => 'Área de química',
        ]);

        $phase = Phase::create([
            'area_id' => $area->id,
            'name' => 'Final',
            'description' => 'Fase final',
            'start_date' => '2025-10-20',
            'end_date' => '2025-10-25',
        ]);

        $phase->delete();
        $this->assertDatabaseMissing('phases', ['name' => 'Final']);
    }
}
