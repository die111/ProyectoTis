<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LevelSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('levels')->insert([
            ['nombre' => 'Primero de Segundaria'],
            ['nombre' => 'Segundo de Segundaria'],
            ['nombre' => 'Tercero de Segundaria'],
            ['nombre' => 'Cuarto de Segundaria'],
            ['nombre' => 'Quinto de Segundaria'],
            ['nombre' => 'Sexto de Segundaria'],
            ['nombre' => 'Univesidad'],
            ['nombre' => 'Postgrado'],
        ]);
    }
}
