<?php

namespace Tests\System;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Role;
use App\Models\Area;
use App\Models\Permission;
use App\Models\Competicion;
use App\Models\Level;
use App\Models\Inscription;
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

    public function test_flujo_completo_inscripcion_con_notificaciones()
    {
        // 1. Configurar el sistema con permisos y roles
        $area = Area::create(['name' => 'Física', 'is_active' => true]);
        $level = Level::create(['nombre' => 'Avanzado']);

        $permisoInscripcion = Permission::create([
            'name' => 'inscripcion',
            'description' => 'Gestionar inscripciones'
        ]);

        $permisoInscripcionCompetencia = Permission::create([
            'name' => 'inscripcion_competencia',
            'description' => 'Inscribirse a competencias'
        ]);

        $roleAdmin = Role::create(['name' => 'admin', 'description' => 'Administrador']);
        $roleEstudiante = Role::create(['name' => 'estudiante', 'description' => 'Estudiante']);

        $roleAdmin->permissions()->attach($permisoInscripcion->id);
        $roleEstudiante->permissions()->attach($permisoInscripcionCompetencia->id);

        // 2. Crear usuarios
        $admin = User::factory()->create([
            'role_id' => $roleAdmin->id,
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

        // 3. Crear competencia activa
        $competencia = Competicion::create([
            'name' => 'Concurso de Física',
            'description' => 'Competencia de física avanzada',
            'state' => 'activa',
            'fechaInicio' => now()->addDays(1),
            'fechaFin' => now()->addDays(30),
            'area_id' => $area->id,
        ]);

        // 4. Estudiante se inscribe
        $response = $this->actingAs($estudiante)
            ->postJson("/inscripcion/{$competencia->id}", [
                'area_id' => $area->id,
                'level_id' => $level->id,
                'es_grupal' => false,
                'observaciones' => 'Solicitud de inscripción de prueba'
            ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        // 5. Verificar que la inscripción se creó con estado pendiente
        $inscripcion = Inscription::where('user_id', $estudiante->id)
            ->where('competition_id', $competencia->id)
            ->first();

        $this->assertNotNull($inscripcion);
        $this->assertEquals('pendiente', $inscripcion->estado);

        // 6. Verificar que el admin recibió la notificación en la BD
        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => (string) $admin->id,
            'notifiable_type' => User::class,
        ]);

        $notificacion = $admin->notifications()->first();
        $this->assertNotNull($notificacion);
        $this->assertNull($notificacion->read_at);

        $data = $notificacion->data;
        $this->assertEquals('Nueva Inscripción Pendiente', $data['titulo']);
        $this->assertStringContainsString($estudiante->name, $data['mensaje']);
        $this->assertStringContainsString($competencia->name, $data['mensaje']);

        // 7. Admin ve y marca la notificación como leída
        $this->actingAs($admin)
            ->postJson("/notifications/{$notificacion->id}/read")
            ->assertStatus(200)
            ->assertJson(['success' => true]);

        $notificacion->refresh();
        $this->assertNotNull($notificacion->read_at);

        // 8. Admin actualiza el estado de la inscripción a confirmada
        $response = $this->actingAs($admin)
            ->postJson("/dashboard/admin/inscripcion/solicitud/{$inscripcion->id}/estado", [
                'estado' => 'confirmada',
                'observaciones' => 'Inscripción aprobada'
            ]);

        $response->assertStatus(200);

        // 9. Verificar que el estudiante recibió notificación de aprobación
        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => (string) $estudiante->id,
            'notifiable_type' => User::class,
        ]);

        $notifEstudiante = $estudiante->notifications()->first();
        $this->assertNotNull($notifEstudiante);
        $dataEstudiante = $notifEstudiante->data;
        $this->assertEquals('¡Inscripción Aprobada!', $dataEstudiante['titulo']);

        // 10. Verificar estado final de la inscripción
        $inscripcion->refresh();
        $this->assertEquals('confirmada', $inscripcion->estado);
    }

    public function test_notificaciones_solo_a_usuarios_activos_con_permiso()
    {
        $area = Area::create(['name' => 'Química', 'is_active' => true]);
        $level = Level::create(['nombre' => 'Básico']);

        $permisoInscripcion = Permission::create([
            'name' => 'inscripcion',
            'description' => 'Gestionar inscripciones'
        ]);

        $permisoInscripcionCompetencia = Permission::create([
            'name' => 'inscripcion_competencia',
            'description' => 'Inscribirse a competencias'
        ]);

        $roleAdmin = Role::create(['name' => 'admin', 'description' => 'Administrador']);
        $roleCoordinador = Role::create(['name' => 'coordinador', 'description' => 'Coordinador']);
        $roleEstudiante = Role::create(['name' => 'estudiante', 'description' => 'Estudiante']);

        // Solo admin tiene permiso de inscripción
        $roleAdmin->permissions()->attach($permisoInscripcion->id);
        
        // Estudiante tiene permiso de inscribirse a competencias
        $roleEstudiante->permissions()->attach($permisoInscripcionCompetencia->id);

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

        $coordinador = User::factory()->create([
            'role_id' => $roleCoordinador->id,
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

        $competencia = Competicion::create([
            'name' => 'Olimpiada de Química',
            'description' => 'Competencia de química',
            'state' => 'activa',
            'fechaInicio' => now()->addDays(1),
            'fechaFin' => now()->addDays(30),
            'area_id' => $area->id,
        ]);

        $this->actingAs($estudiante)
            ->postJson("/inscripcion/{$competencia->id}", [
                'area_id' => $area->id,
                'level_id' => $level->id,
                'es_grupal' => false,
            ]);

        // Solo el admin activo debe tener notificación
        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => (string) $adminActivo->id,
            'notifiable_type' => User::class,
        ]);

        // El admin inactivo NO debe tener notificación
        $this->assertDatabaseMissing('notifications', [
            'notifiable_id' => (string) $adminInactivo->id,
        ]);

        // El coordinador sin permiso NO debe tener notificación
        $this->assertDatabaseMissing('notifications', [
            'notifiable_id' => (string) $coordinador->id,
        ]);

        // Verificar conteo
        $countAdminActivo = $adminActivo->notifications()->count();
        $countAdminInactivo = $adminInactivo->notifications()->count();
        $countCoordinador = $coordinador->notifications()->count();

        $this->assertEquals(1, $countAdminActivo);
        $this->assertEquals(0, $countAdminInactivo);
        $this->assertEquals(0, $countCoordinador);
    }
}
