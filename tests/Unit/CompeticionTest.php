<?php
namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Competicion;

class CompeticionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        // Simular usuario admin autenticado
        parent::setUp();
        $adminRole = \App\Models\Role::create([
            'name' => 'admin',
            'description' => 'Administrador',
        ]);
        $area = \App\Models\Area::create([
            'name' => 'General',
            'description' => 'Área general',
        ]);
        $admin = \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role_id' => $adminRole->id,
            'area_id' => $area->id,
            'is_active' => true,
            'user_code' => 'ADM001',
        ]);
        $this->be(\App\Models\User::find($admin->id));
    }

    /** @test */
    public function crear_competicion()
    {
        $competicion = Competicion::create([
            'name' => 'Olimpiada de Matemáticas',
            'description' => 'Competencia anual de matemáticas',
            'state' => 'activa',
            'fechaInicio' => '2025-10-10',
            'fechaFin' => '2025-10-15',
        ]);
        $this->assertDatabaseHas('competicions', [
            'name' => 'Olimpiada de Matemáticas',
            'state' => 'activa',
        ]);
        $this->assertEquals('Olimpiada de Matemáticas', $competicion->name);
        $this->assertEquals('activa', $competicion->state);
    }
}
