<?php
namespace Tests\System;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Reclamo;
use App\Models\User;
use App\Models\Role;
use App\Models\Area;

class SistemaAuditoriaReclamoTest extends TestCase
{
    use RefreshDatabase;

    public function test_creacion_y_actualizacion_de_reclamo_genera_auditorias()
    {
        $role = Role::create(['name' => 'admin', 'description' => 'admin']);
        $area = Area::create(['name' => 'Area S', 'description' => 's', 'is_active' => true]);
        $user = User::factory()->create(['role_id' => $role->id, 'area_id' => $area->id, 'user_code' => 'SYS001']);

        $this->be($user);

        // Crear recursos necesarios para la inscripción requerida por reclamos
        $compet = \App\Models\Competicion::create([
            'name' => 'Comp Test',
            'description' => 'd',
            'fechaInicio' => now(),
            'fechaFin' => now()->addDays(10),
        ]);
        $categoria = \App\Models\Categoria::create(['nombre' => 'Cat A']);
        $inscription = \App\Models\Inscription::create([
            'competition_id' => $compet->id,
            'user_id' => $user->id,
            'area_id' => $area->id,
            'categoria_id' => $categoria->id,
            'fase' => '1',
            'estado' => 'pendiente',
            'is_active' => true
        ]);

        $reclamo = Reclamo::create([
            'inscription_id' => $inscription->id,
            'user_id' => $user->id,
            'mensaje' => 'Mensaje prueba',
            'estado' => 'pendiente'
        ]);

        $this->assertDatabaseHas('audits', ['auditable_type' => Reclamo::class, 'auditable_id' => $reclamo->id, 'action' => 'created']);

        $reclamo->update(['estado' => 'atendido']);

        $this->assertDatabaseHas('audits', ['auditable_type' => Reclamo::class, 'auditable_id' => $reclamo->id, 'action' => 'updated']);
    }
}
