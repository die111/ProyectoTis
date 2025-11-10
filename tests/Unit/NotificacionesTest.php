<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use App\Notifications\FrontNotification;
use App\Models\User;
use App\Models\Role;
use App\Models\Area;
use App\Models\Permission;
use App\Models\Competicion;
use App\Models\Inscription;
use App\Models\Level;
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

    public function test_envia_notificacion_a_usuarios_con_permiso_inscripcion()
    {
        Notification::fake();

        // Crear áreas
        $area = Area::create(['name' => 'Test Area', 'is_active' => true]);

        // Crear permisos
        $permisoInscripcion = Permission::create([
            'name' => 'inscripcion',
            'description' => 'Gestionar inscripciones'
        ]);

        // Crear roles
        $roleAdmin = Role::create(['name' => 'admin', 'description' => 'Administrador']);
        $roleEstudiante = Role::create(['name' => 'estudiante', 'description' => 'Estudiante']);
        $roleResponsable = Role::create(['name' => 'responsable', 'description' => 'Responsable']);

        // Asignar permiso de inscripción a admin y responsable
        $roleAdmin->permissions()->attach($permisoInscripcion->id);
        $roleResponsable->permissions()->attach($permisoInscripcion->id);

        // Crear usuarios
        $admin = User::factory()->create([
            'role_id' => $roleAdmin->id,
            'area_id' => $area->id,
            'user_code' => Str::random(10),
            'is_active' => true,
        ]);

        $responsable = User::factory()->create([
            'role_id' => $roleResponsable->id,
            'area_id' => $area->id,
            'user_code' => Str::random(10),
            'is_active' => true,
        ]);

        $estudiante = User::factory()->create([
            'role_id' => $roleEstudiante->id,
            'area_id' => $area->id,
            'user_code' => Str::random(10),
            'is_active' => true,
        ]);

        // Simular envío de notificaciones (como lo hace InscripcionController)
        $permission = Permission::where('name', 'inscripcion')->first();
        if ($permission) {
            $roleIds = $permission->roles()->pluck('roles.id');
            $usersToNotify = User::whereIn('role_id', $roleIds)
                ->where('is_active', true)
                ->get();

            foreach ($usersToNotify as $userToNotify) {
                $userToNotify->notify(new FrontNotification(
                    'Nueva Inscripción Pendiente',
                    'Un estudiante se ha inscrito y requiere aprobación.',
                    'info',
                    '/admin/inscripcion/solicitud'
                ));
            }
        }

        // Verificar que admin y responsable recibieron notificación
        Notification::assertSentTo($admin, FrontNotification::class);
        Notification::assertSentTo($responsable, FrontNotification::class);

        // Verificar que estudiante NO recibió notificación
        Notification::assertNotSentTo($estudiante, FrontNotification::class);

        // Verificar que se enviaron exactamente 2 notificaciones
        Notification::assertCount(2);
    }

    public function test_usuarios_inactivos_no_reciben_notificaciones_de_inscripcion()
    {
        Notification::fake();

        $area = Area::create(['name' => 'Test Area', 'is_active' => true]);

        $permisoInscripcion = Permission::create([
            'name' => 'inscripcion',
            'description' => 'Gestionar inscripciones'
        ]);

        $roleAdmin = Role::create(['name' => 'admin', 'description' => 'Administrador']);
        $roleAdmin->permissions()->attach($permisoInscripcion->id);

        $adminActivo = User::factory()->create([
            'role_id' => $roleAdmin->id,
            'area_id' => $area->id,
            'user_code' => Str::random(10),
            'is_active' => true,
        ]);

        $adminInactivo = User::factory()->create([
            'role_id' => $roleAdmin->id,
            'area_id' => $area->id,
            'user_code' => Str::random(10),
            'is_active' => false,
        ]);

        // Simular envío de notificaciones
        $permission = Permission::where('name', 'inscripcion')->first();
        if ($permission) {
            $roleIds = $permission->roles()->pluck('roles.id');
            $usersToNotify = User::whereIn('role_id', $roleIds)
                ->where('is_active', true)
                ->get();

            foreach ($usersToNotify as $userToNotify) {
                $userToNotify->notify(new FrontNotification(
                    'Nueva Inscripción Pendiente',
                    'Un estudiante se ha inscrito y requiere aprobación.',
                    'info',
                    '/admin/inscripcion/solicitud'
                ));
            }
        }

        // Solo el admin activo debe recibir notificación
        Notification::assertSentTo($adminActivo, FrontNotification::class);
        Notification::assertNotSentTo($adminInactivo, FrontNotification::class);
        Notification::assertCount(1);
    }
}
