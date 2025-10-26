<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            ['name' => 'dashboard', 'description' => 'Acceso al dashboard'],
            ['name' => 'competicion', 'description' => 'Gestionar competiciones'],
            ['name' => 'roles', 'description' => 'Gestionar roles'],
            ['name' => 'usuarios', 'description' => 'Gestionar usuarios'],
            ['name' => 'inscripcion', 'description' => 'Gestionar inscripciones'],
            ['name' => 'fases', 'description' => 'Gestionar fases'],
            ['name' => 'areas', 'description' => 'Gestionar áreas'],
            ['name' => 'categorias', 'description' => 'Gestionar categorías'],
            ['name' => 'evaluaciones', 'description' => 'Gestionar evaluaciones'],
        ];
        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm['name']], $perm);
        }
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminRole->permissions()->sync(Permission::pluck('id')->toArray());
        }
    }
}
