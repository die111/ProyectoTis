<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use App\Notifications\FrontNotification;
use App\Models\User;
use App\Models\Role;
use App\Models\Area;
use App\Models\Permission;
use App\Models\Competicion;
use App\Models\Level;
use App\Models\Inscription;
use Illuminate\Support\Facades\DB;
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
        DB::table('notifications')->truncate();

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

    public function test_inscripcion_envia_notificacion_a_usuarios_con_permiso()
    {
        Notification::fake();

        // Crear área y level
        $area = Area::create(['name' => 'Matemáticas', 'is_active' => true]);
        $level = Level::create(['nombre' => 'Básico']);

        // Crear permisos
        $permisoInscripcion = Permission::create([
            'name' => 'inscripcion',
            'description' => 'Gestionar inscripciones'
        ]);

        $permisoInscripcionCompetencia = Permission::create([
            'name' => 'inscripcion_competencia',
            'description' => 'Inscribirse a competencias'
        ]);

        // Crear roles
        $roleAdmin = Role::create(['name' => 'admin', 'description' => 'Administrador']);
        $roleEstudiante = Role::create(['name' => 'estudiante', 'description' => 'Estudiante']);

        // Asignar permisos
        $roleAdmin->permissions()->attach($permisoInscripcion->id);
        $roleEstudiante->permissions()->attach($permisoInscripcionCompetencia->id);

        // Crear usuarios
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

        // Crear competencia activa
        $competencia = Competicion::create([
            'name' => 'Olimpiada Matemática',
            'description' => 'Competencia de matemáticas',
            'state' => 'activa',
            'fechaInicio' => now()->addDays(1),
            'fechaFin' => now()->addDays(30),
            'area_id' => $area->id,
        ]);

        // Hacer la inscripción como estudiante
        $response = $this->actingAs($estudiante)
            ->postJson("/inscripcion/{$competencia->id}", [
                'area_id' => $area->id,
                'level_id' => $level->id,
                'es_grupal' => false,
            ]);

        $response->assertStatus(200);

        // Verificar que el admin recibió la notificación
        Notification::assertSentTo($admin, function (FrontNotification $notification, $channels) use ($estudiante, $competencia) {
            $data = $notification->toArray($admin ?? new User());
            return $data['titulo'] === 'Nueva Inscripción Pendiente' &&
                   str_contains($data['mensaje'], $estudiante->name) &&
                   str_contains($data['mensaje'], $competencia->name);
        });

        // Verificar que el estudiante NO recibió notificación
        Notification::assertNotSentTo($estudiante, FrontNotification::class);
    }

    public function test_multiples_usuarios_con_permiso_reciben_notificacion()
    {
        $area = Area::create(['name' => 'Ciencias', 'is_active' => true]);
        $level = Level::create(['nombre' => 'Intermedio']);

        $permisoInscripcion = Permission::create([
            'name' => 'inscripcion',
            'description' => 'Gestionar inscripciones'
        ]);

        $permisoInscripcionCompetencia = Permission::create([
            'name' => 'inscripcion_competencia',
            'description' => 'Inscribirse a competencias'
        ]);

        $roleAdmin = Role::create(['name' => 'admin', 'description' => 'Administrador']);
        $roleResponsable = Role::create(['name' => 'responsable', 'description' => 'Responsable']);
        $roleEstudiante = Role::create(['name' => 'estudiante', 'description' => 'Estudiante']);

        // Ambos roles tienen permiso de gestionar inscripciones
        $roleAdmin->permissions()->attach($permisoInscripcion->id);
        $roleResponsable->permissions()->attach($permisoInscripcion->id);
        
        // Estudiante tiene permiso de inscribirse
        $roleEstudiante->permissions()->attach($permisoInscripcionCompetencia->id);

        $admin1 = User::factory()->create([
            'role_id' => $roleAdmin->id,
            'area_id' => $area->id,
            'user_code' => Str::random(10),
            'is_active' => true,
        ]);

        $admin2 = User::factory()->create([
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

        $competencia = Competicion::create([
            'name' => 'Feria de Ciencias',
            'description' => 'Competencia científica',
            'state' => 'activa',
            'fechaInicio' => now()->addDays(1),
            'fechaFin' => now()->addDays(30),
            'area_id' => $area->id,
        ]);

        // Limpiar notificaciones previas
        DB::table('notifications')->truncate();

        $response = $this->actingAs($estudiante)
            ->postJson("/inscripcion/{$competencia->id}", [
                'area_id' => $area->id,
                'level_id' => $level->id,
                'es_grupal' => false,
            ]);

        $response->assertStatus(200);

        // Verificar que los usuarios con permiso recibieron notificaciones
        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => (string) $admin1->id,
            'notifiable_type' => User::class,
        ]);

        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => (string) $admin2->id,
            'notifiable_type' => User::class,
        ]);

        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => (string) $responsable->id,
            'notifiable_type' => User::class,
        ]);

        // El estudiante no debe tener notificación
        $this->assertDatabaseMissing('notifications', [
            'notifiable_id' => (string) $estudiante->id,
        ]);

        // Debe haber exactamente 3 notificaciones
        $totalNotifications = DB::table('notifications')->count();
        $this->assertEquals(3, $totalNotifications);
    }
}
