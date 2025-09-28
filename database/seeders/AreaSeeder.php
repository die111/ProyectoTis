<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AreaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('areas')->insert([
            [
                'name' => 'Matemáticas',
                'description' => 'Área de matemáticas',
            ],
            [
                'name' => 'Física',
                'description' => 'Área de física',
            ],
            [
                'name' => 'Química',
                'description' => 'Área de química',
            ],
        ]);
    }
}
