<?php

namespace Tests\System;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Role;
use App\Models\Area;
use Illuminate\Support\Str;

class NotificacionesSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_flujo_marcar_notificacion_como_leida()
    {
        $role = Role::create(['name' => 'role-system-notif', 'description' => 'role']);
        $area = Area::create(['name' => 'Area System', 'is_active' => true]);

        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'role_id' => $role->id,
            'area_id' => $area->id,
            'user_code' => Str::random(10),
        ]);

        // Enviar notificación mediante el método del modelo (simula acción de rol admin)
        $user->notify(new \App\Notifications\FrontNotification('Sistema', 'Prueba', 'info', '/'));

        $notification = $user->notifications()->first();
        $this->assertNull($notification->read_at);

        // Marcar como leída vía ruta POST protegida
        $this->actingAs($user)
            ->postJson('/notifications/'.$notification->id.'/read')
            ->assertStatus(200)
            ->assertJson(['success' => true]);

        $notification->refresh();
        $this->assertNotNull($notification->read_at);
    }
}
