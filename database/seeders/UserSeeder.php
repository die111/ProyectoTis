<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usuario administrador
        User::create([
            'name' => 'Administrador Sistema',
            'email' => 'admin@ohsansi.edu.bo',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Usuarios responsables de área
        $areas = ['Matemática', 'Física', 'Química', 'Biología', 'Informática', 'Robótica', 'Astronomía', 'Geografía'];
        
        foreach ($areas as $area) {
            User::create([
                'name' => 'Responsable ' . $area,
                'email' => strtolower('responsable.' . str_replace(' ', '', $area) . '@ohsansi.edu.bo'),
                'password' => Hash::make('  '),
                'role' => 'responsable_area',
                'area' => $area,
                'is_active' => true,
            ]);
        }

        // Evaluadores de ejemplo
        $evaluadores = [
            ['name' => 'Dr. Juan Pérez', 'area' => 'Matemática'],
            ['name' => 'Dra. María González', 'area' => 'Física'],
            ['name' => 'Ing. Carlos Rodríguez', 'area' => 'Informática'],
            ['name' => 'Lic. Ana Torres', 'area' => 'Química'],
            ['name' => 'Prof. Luis Morales', 'area' => 'Biología'],
        ];

        foreach ($evaluadores as $evaluador) {
            User::create([
                'name' => $evaluador['name'],
                'email' => strtolower(str_replace([' ', '.'], ['', ''], $evaluador['name']) . '@ohsansi.edu.bo'),
                'password' => Hash::make('evaluador123'),
                'role' => 'evaluador',
                'area' => $evaluador['area'],
                'is_active' => true,
            ]);
        }

        // Crear coordinador
        User::create([
            'name' => 'Coordinador General',
            'email' => 'coordinador@ohsansi.edu.bo',
            'password' => Hash::make('coordinador123'),
            'role' => 'coordinador',
            'is_active' => true,
        ]);
    }
}