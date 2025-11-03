<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'admin', 'description' => 'Administrador del sistema'],
            ['name' => 'responsable_area', 'description' => 'Responsable de gestionar y administrar un area especifica del sistema'],
            ['name' => 'evaluador', 'description' => 'Usuario encargado de evaluar y calificar participantes en las competencias'],
            ['name' => 'coordinador', 'description' => 'Cordinador de eventos y actividades dentro del sistema'],
            ['name' => 'estudiante', 'description' => 'Estudiante participante en competencias'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role['name']], $role);
        }
    }
}

