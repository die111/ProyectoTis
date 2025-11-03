<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use App\Notifications\FrontNotification;
use App\Models\User;
use App\Models\Role;
use App\Models\Area;
use Illuminate\Support\Str;
use Illuminate\Notifications\DatabaseNotification;

class NotificacionesTest extends TestCase
{
    use RefreshDatabase;

    public function test_envia_notificacion_front_a_usuario_en_bd()
    {
        Notification::fake();

        $role = Role::first() ?: Role::create(['name' => 'test-role', 'description' => 'role for tests']);
        $area = Area::first() ?: Area::create(['name' => 'Test Area', 'is_active' => true]);
        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'role_id' => $role->id,
            'area_id' => $area->id,
            'user_code' => Str::random(10),
        ]);

        $user->notify(new FrontNotification('Titulo', 'Mensaje de prueba', 'info', route('dashboard')));

        Notification::assertSentTo($user, function (FrontNotification $notification, $channels) use ($user) {
            $data = $notification->toArray($user);
            $this->assertArrayHasKey('titulo', $data);
            $this->assertArrayHasKey('mensaje', $data);
            $this->assertArrayHasKey('url', $data);
            return true;
        });
    }

    public function test_marca_una_notificacion_como_leida()
    {
        $role = Role::first() ?: Role::create(['name' => 'test-role', 'description' => 'role for tests']);
        $area = Area::first() ?: Area::create(['name' => 'Test Area', 'is_active' => true]);
        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'role_id' => $role->id,
            'area_id' => $area->id,
            'user_code' => Str::random(10),
        ]);

        // crear una notificación en la base de datos
        $user->notify(new FrontNotification('Revisa', 'La inscripción fue revisada', 'info', '/'));
        $notification = $user->notifications()->first();
        $this->assertNull($notification->read_at);

        $response = $this->actingAs($user)
            ->postJson('/notifications/'.$notification->id.'/read');

        $response->assertStatus(200)->assertJson(['success' => true]);

        $notification->refresh();
        $this->assertNotNull($notification->read_at);
    }

    public function test_marca_todas_las_notificaciones_como_leidas()
    {
        $role = Role::first() ?: Role::create(['name' => 'test-role', 'description' => 'role for tests']);
        $area = Area::first() ?: Area::create(['name' => 'Test Area', 'is_active' => true]);
        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'role_id' => $role->id,
            'area_id' => $area->id,
            'user_code' => Str::random(10),
        ]);

        // crear múltiples notificaciones
        $user->notify(new FrontNotification('N1', 'Uno', 'info', '/'));
        $user->notify(new FrontNotification('N2', 'Dos', 'info', '/'));

        $this->assertGreaterThan(0, $user->unreadNotifications()->count());

        $response = $this->actingAs($user)
            ->postJson('/notifications/mark-all-read');

        $response->assertStatus(200)->assertJson(['success' => true]);

        $this->assertEquals(0, $user->fresh()->unreadNotifications()->count());
    }
}
