<?php
namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Phase;
use App\Models\Area;
use App\Models\User;
use App\Models\Role;

class PhaseAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
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
    public function crear_fase()
    {
        $phase = Phase::create([
            'name' => 'Inscripción',
            'description' => 'Fase de inscripción',
            'is_active' => true,
        ]);
        $this->assertDatabaseHas('phases', [
            'name' => 'Inscripción',
        ]);
    }

    /** @test */
    public function activar_o_desactivar_fase()
    {
        $phase = Phase::create([
            'name' => 'Evaluación',
            'description' => 'Fase de evaluación',
            'is_active' => false,
        ]);
        $phase->is_active = true;
        $phase->save();
        $this->assertTrue($phase->fresh()->is_active);
        $phase->is_active = false;
        $phase->save();
        $this->assertFalse($phase->fresh()->is_active);
    }
}
