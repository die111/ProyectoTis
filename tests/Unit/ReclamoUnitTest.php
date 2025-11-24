<?php
namespace Tests\Unit;

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

class ReclamoUnitTest extends TestCase
{
    use RefreshDatabase;

    public function test_creacion_de_reclamo_genera_registro_en_bitacora()
    {
        $role = Role::create(['name' => 'admin', 'description' => 'admin']);
        $area = Area::create(['name' => 'Area U', 'description' => 'u', 'is_active' => true]);
        $user = User::factory()->create(['role_id' => $role->id, 'area_id' => $area->id, 'user_code' => 'UT-001']);

        $compet = Competicion::create([
            'name' => 'Competicion U',
            'description' => 'desc',
            'fechaInicio' => now(),
            'fechaFin' => now()->addDays(3),
        ]);
        $categoria = Categoria::create(['nombre' => 'Cat U']);

        $inscription = Inscription::create([
            'competition_id' => $compet->id,
            'user_id' => $user->id,
            'area_id' => $area->id,
            'categoria_id' => $categoria->id,
            'fase' => '1',
            'estado' => 'pendiente',
            'is_active' => true,
        ]);

        $reclamo = Reclamo::create(['inscription_id' => $inscription->id, 'user_id' => $user->id, 'mensaje' => 'Prueba unidad', 'estado' => 'pendiente']);

        $audit = Audit::where('auditable_type', Reclamo::class)->where('auditable_id', $reclamo->id)->first();
        $this->assertNotNull($audit, 'Se esperaba un registro de auditoría al crear el reclamo');
    }
}
