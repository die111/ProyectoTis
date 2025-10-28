<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            ['name' => 'admin', 'description' => 'Administrador del sistema', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'responsable_area', 'description' => 'Responsable de gestionar y administrar un area especifica del sistema', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'evaluador', 'description' => 'Usuario encargado de evaluar y calificar participantes en las competencias', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'coordinador', 'description' => 'Cordinador de eventos y actividades dentro del sistema', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'estudiante', 'description' => 'Participante inscrito en las competencias', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}

