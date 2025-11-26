<?php
namespace Tests\System;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Role;
use App\Models\Area;
use App\Models\User;
use App\Models\Competicion;
use App\Models\Categoria;
use App\Models\Inscription;
use App\Models\Reclamo;
use App\Models\Audit;

class ReclamoSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_creacion_y_actualizacion_de_reclamo_generan_auditoria()
    {
        $role = Role::create(['name' => 'admin', 'description' => 'admin']);
        $area = Area::create(['name' => 'Area S', 'description' => 's', 'is_active' => true]);
        $user = User::factory()->create(['role_id' => $role->id, 'area_id' => $area->id, 'user_code' => 'ST-001']);

        $compet = Competicion::create([
            'name' => 'Competicion S',
            'description' => 'desc',
            'fechaInicio' => now(),
            'fechaFin' => now()->addDays(4),
        ]);
        $categoria = Categoria::create(['nombre' => 'Cat S']);

        $inscription = Inscription::create([
            'competition_id' => $compet->id,
            'user_id' => $user->id,
            'area_id' => $area->id,
            'categoria_id' => $categoria->id,
            'fase' => '1',
            'estado' => 'pendiente',
            'is_active' => true,
        ]);

        $reclamo = Reclamo::create(['inscription_id' => $inscription->id, 'user_id' => $user->id, 'mensaje' => 'Prueba sistema', 'estado' => 'pendiente']);

        // Verificar creación: debe existir al menos un registro de auditoría
        $auditsAfterCreate = Audit::where('auditable_type', Reclamo::class)->where('auditable_id', $reclamo->id)->get();
        $this->assertTrue($auditsAfterCreate->count() >= 1, 'Debe existir al menos 1 audit tras crear el reclamo');

        // Actualizar el reclamo
        $reclamo->mensaje = 'Prueba sistema - editado';
        $reclamo->save();

        $auditsAfterUpdate = Audit::where('auditable_type', Reclamo::class)->where('auditable_id', $reclamo->id)->get();
        $this->assertTrue($auditsAfterUpdate->count() >= 2, 'Debe existir al menos 2 audits tras modificar el reclamo');
    }
}
