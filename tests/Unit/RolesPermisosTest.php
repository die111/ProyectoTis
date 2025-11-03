<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use App\Models\Area;
use Illuminate\Support\Str;
use App\Http\Middleware\PermissionMiddleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\Auth;

class RolesPermisosTest extends TestCase
{
    use RefreshDatabase;

    public function test_asignar_permiso_a_rol()
    {
        $role = Role::create(['name' => 'rol-test', 'description' => 'Rol de prueba']);
        $perm = Permission::create(['name' => 'inscribir', 'description' => 'Permite inscribir en competencia']);

        // Asociar permiso al rol
        $role->permissions()->attach($perm->id);

        $this->assertDatabaseHas('role_permission', [
            'role_id' => $role->id,
            'permission_id' => $perm->id,
        ]);

        $this->assertTrue($role->permissions->pluck('name')->contains('inscribir'));
    }

    public function test_usuario_con_rol_tiene_permiso_y_middleware_permite()
    {
        $role = Role::create(['name' => 'rol-test2', 'description' => 'Rol de prueba 2']);
        $perm = Permission::create(['name' => 'ver_panel', 'description' => 'Permite ver el panel']);
        $role->permissions()->attach($perm->id);

        $area = Area::create(['name' => 'Area Test', 'is_active' => true]);

        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'role_id' => $role->id,
            'area_id' => $area->id,
            'user_code' => Str::random(10),
        ]);

        // Simular petición que requiere permiso 'ver_panel'
        $middleware = new PermissionMiddleware();
        $request = Request::create('/some-path', 'GET');

        // Authenticate the user for the middleware
        Auth::login($user);

        $response = $middleware->handle($request, function ($req) {
            return 'ok';
        }, 'ver_panel');

        $this->assertEquals('ok', $response);
    }

    public function test_usuario_sin_permiso_es_rechazado_por_middleware()
    {
        $role = Role::create(['name' => 'rol-test3', 'description' => 'Rol de prueba 3']);
        $perm = Permission::create(['name' => 'perm_externa', 'description' => 'Otra']);

        $area = Area::create(['name' => 'Area Test 2', 'is_active' => true]);

        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'role_id' => $role->id,
            'area_id' => $area->id,
            'user_code' => Str::random(10),
        ]);

        // Simular petición que requiere permiso 'no_existente'
        $middleware = new PermissionMiddleware();
        $request = Request::create('/some-path', 'GET');

    // Emular autenticación de usuario
    Auth::login($user);

        $this->expectException(HttpException::class);
        $middleware->handle($request, function ($req) {
            return 'ok';
        }, 'no_existente');
    }
}
