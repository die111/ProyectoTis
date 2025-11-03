<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use App\Notifications\FrontNotification;
use App\Models\User;
use App\Models\Role;
use App\Models\Area;
use Illuminate\Support\Str;

class NotificacionesFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_ruta_de_prueba_envia_notificacion_y_se_registra_en_bd()
    {
        // Datos necesarios para crear el usuario
        $role = Role::create(['name' => 'role-notifications', 'description' => 'role']);
        $area = Area::create(['name' => 'Area Notif', 'is_active' => true]);

        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'role_id' => $role->id,
            'area_id' => $area->id,
            'user_code' => Str::random(10),
        ]);

        // Limpiar notificaciones
        \DB::table('notifications')->truncate();

        // Ejecutar la ruta que envía la notificación
        $this->actingAs($user)
            ->get('/test-notification')
            ->assertStatus(200)
            ->assertSee('Notificación enviada');

        // La notificación debe estar en la BD para el usuario
        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => (string) $user->id,
            'notifiable_type' => User::class,
        ]);
    }
}
