<?php

namespace Database\Seeders;

use Faker\Provider\ar_EG\Person;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            AreaSeeder::class,
            PhaseSeeder::class,
            UserSeeder::class,
            LevelSeeder::class,
            PermissionSeeder::class,
            CategoriaSeeder::class,
        ]);
    }
}
