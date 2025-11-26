<?php
namespace Tests\Feature;

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

class ReclamoFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_rutas_index_y_show_de_auditoria_para_reclamos()
    {
        $role = Role::create(['name' => 'admin', 'description' => 'admin']);
        $area = Area::create(['name' => 'Area F', 'description' => 'f', 'is_active' => true]);
        $user = User::factory()->create(['role_id' => $role->id, 'area_id' => $area->id, 'user_code' => 'FT-001']);

        $this->actingAs($user);

        // La ruta index debe ser accesible
        $res = $this->get(route('admin.audits.index'));
        $res->assertStatus(200)->assertSee('Bitácora');

        // Crear recursos y reclamo
        $compet = Competicion::create([
            'name' => 'Comp F',
            'description' => 'desc',
            'fechaInicio' => now(),
            'fechaFin' => now()->addDays(5),
        ]);
        $categoria = Categoria::create(['nombre' => 'Cat F']);
        $inscription = Inscription::create([
            'competition_id' => $compet->id,
            'user_id' => $user->id,
            'area_id' => $area->id,
            'categoria_id' => $categoria->id,
            'fase' => '1',
            'estado' => 'pendiente',
            'is_active' => true
        ]);

        $reclamo = Reclamo::create(['inscription_id' => $inscription->id, 'user_id' => $user->id, 'mensaje' => 'prueba feature', 'estado' => 'pendiente']);

        $audit = Audit::where('auditable_type', Reclamo::class)->where('auditable_id', $reclamo->id)->first();
        $this->assertNotNull($audit);

        $res2 = $this->get(route('admin.audits.show', $audit->id));
        $res2->assertStatus(200)->assertSee(class_basename($audit->auditable_type))->assertSee('prueba feature');
    }
}
