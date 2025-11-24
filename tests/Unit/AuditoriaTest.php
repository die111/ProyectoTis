<?php
namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\AuditService;
use App\Models\Audit;
use App\Models\Area;
use App\Models\User;
use App\Models\Role;

class ServicioAuditoriaTest extends TestCase
{
    use RefreshDatabase;

    public function test_registra_entrada_de_auditoria()
    {
        // Preparar usuario
        $role = Role::create(['name' => 'admin', 'description' => 'admin role']);
        $area = Area::create(['name' => 'Test area', 'description' => 'x', 'is_active' => true]);
        $user = User::factory()->create(['role_id' => $role->id, 'area_id' => $area->id, 'user_code' => 'UTST001']);
        $this->be($user);

        $areaModel = Area::create(['name' => 'Area Audit', 'description' => 'desc']);

        $service = app(AuditService::class);
        $service->record($areaModel, 'created');

        $this->assertDatabaseHas('audits', [
            'auditable_type' => get_class($areaModel),
            'auditable_id' => $areaModel->getKey(),
            'action' => 'created'
        ]);
    }
}
