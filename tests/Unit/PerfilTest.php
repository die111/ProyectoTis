<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PerfilTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_autenticado_puede_ver_perfil()
    {
        // Crear role y area necesarios
        $roleId = DB::table('roles')->insertGetId(['name' => 'test-role', 'created_at' => now(), 'updated_at' => now()]);
        $areaId = DB::table('areas')->insertGetId(['name' => 'Test Area', 'created_at' => now(), 'updated_at' => now()]);

        $user = User::factory()->create(['role_id' => $roleId, 'area_id' => $areaId, 'user_code' => 'TEST001']);

        $this->actingAs($user)
            ->get(route('profile.show'))
            ->assertStatus(200)
            ->assertSee($user->name)
            ->assertSee($user->email);
    }

    public function test_usuario_autenticado_puede_actualizar_perfil_con_foto()
    {
        Storage::fake('public');

        $roleId = DB::table('roles')->insertGetId(['name' => 'test-role-2', 'created_at' => now(), 'updated_at' => now()]);
        $areaId = DB::table('areas')->insertGetId(['name' => 'Test Area 2', 'created_at' => now(), 'updated_at' => now()]);

        $user = User::factory()->create(['role_id' => $roleId, 'area_id' => $areaId, 'user_code' => 'TEST002']);

        $file = UploadedFile::fake()->image('photo.jpg');

        $this->actingAs($user)
            ->followingRedirects()
            ->put(route('profile.update'), [
                'name' => 'Nuevo Nombre',
                'email' => 'nuevo' . $user->id . '@example.com',
                'last_name_father' => 'Papá',
                'last_name_mother' => 'Mamá',
                'telephone_number' => '70000000',
                'address' => 'Calle Test',
                'date_of_birth' => '1990-01-01',
                'profile_photo' => $file,
            ])
            ->assertStatus(200)
            ->assertSee('Perfil actualizado');

        $user->refresh();

        $this->assertEquals('Nuevo Nombre', $user->name);
        Storage::disk('public')->assertExists($user->profile_photo);
    }
}
