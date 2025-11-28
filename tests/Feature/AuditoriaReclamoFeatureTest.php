<?php
namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Reclamo;
use App\Models\User;
use App\Models\Role;
use App\Models\Area;
use App\Models\Audit;

class AuditoriaReclamoFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_rutas_index_y_show_de_auditoria_funcionan_para_admin()
    {
        $role = Role::create(['name' => 'admin', 'description' => 'admin']);
        $area = Area::create(['name' => 'Area F', 'description' => 'f', 'is_active' => true]);
        $user = User::factory()->create(['role_id' => $role->id, 'area_id' => $area->id, 'user_code' => 'FEAT001']);

        $this->actingAs($user);

        // La ruta index debe ser accesible
        $res = $this->get(route('admin.audits.index'));
        $res->assertStatus(200)->assertSee('Bitácora');

        // Crear recursos necesarios (competicion, categoria, inscripcion) y luego el reclamo
        $compet = \App\Models\Competicion::create([
            'name' => 'Comp F',
            'description' => 'desc',
            'fechaInicio' => now(),
            'fechaFin' => now()->addDays(5),
        ]);
        $categoria = \App\Models\Categoria::create(['nombre' => 'Cat F']);
        $inscription = \App\Models\Inscription::create([
            'competition_id' => $compet->id,
            'user_id' => $user->id,
            'area_id' => $area->id,
            'categoria_id' => $categoria->id,
            'fase' => '1',
            'estado' => 'pendiente',
            'is_active' => true
        ]);

        $reclamo = Reclamo::create(['inscription_id' => $inscription->id, 'user_id' => $user->id, 'mensaje' => 'prueba', 'estado' => 'pendiente']);

        $audit = Audit::where('auditable_type', Reclamo::class)->where('auditable_id', $reclamo->id)->first();
        $this->assertNotNull($audit);

        // La ruta show debe mostrar los detalles del audit y el historial
        $res2 = $this->get(route('admin.audits.show', $audit->id));
        $res2->assertStatus(200)->assertSee(class_basename($audit->auditable_type))->assertSee('prueba');
    }
}
