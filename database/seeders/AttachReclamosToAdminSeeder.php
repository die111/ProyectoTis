<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

class AttachReclamosToAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permission = Permission::firstOrCreate(
            ['name' => 'reclamos'],
            ['description' => 'Gestionar reclamos']
        );

        $adminRole = Role::where('name', 'admin')->first();

        if ($adminRole) {
            $adminRole->permissions()->syncWithoutDetaching([$permission->id]);
        }
    }
}
