<?php

namespace Tests\System;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Role;
use App\Models\Area;
use Illuminate\Support\Str;

class PerfilSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_flujo_ver_y_actualizar_perfil_con_foto()
    {
        Storage::fake('public');

        $role = Role::create(['name' => 'system-role', 'description' => 'role']);
        $area = Area::create(['name' => 'Area System Perfil', 'is_active' => true]);

        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'role_id' => $role->id,
            'area_id' => $area->id,
            'user_code' => Str::random(10),
        ]);

        // Ver perfil
        $this->actingAs($user)
            ->get(route('profile.show'))
            ->assertStatus(200)
            ->assertSee($user->name)
            ->assertSee($user->email);

        // Actualizar perfil con foto
        $file = UploadedFile::fake()->image('avatar.jpg');
        $this->actingAs($user)
            ->followingRedirects()
            ->put(route('profile.update'), [
                'name' => 'Nombre System',
                'email' => 'nuevo_system@example.com',
                'profile_photo' => $file,
            ])
            ->assertStatus(200)
            ->assertSee('Perfil actualizado');

        $user->refresh();
        $this->assertEquals('Nombre System', $user->name);
        Storage::disk('public')->assertExists($user->profile_photo);
    }
}
