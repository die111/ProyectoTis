<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usuario administrador
        $area = Area::first();
        $adminRoleId = DB::table('roles')->where('name', 'admin')->value('id');
        User::create([
            'name' => 'Administrador Sistema',
            'email' => 'admin@ohsansi.edu.bo',
            'password' => Hash::make('admin123'),
            'role_id' => $adminRoleId,
            'is_active' => true,
            'last_name_father' => 'Admin',
            'last_name_mother' => 'Sistema',
            'area_id' => $area ? $area->id : 1,
            'user_code' => 'ADMIN001',
            'school' => 'OHSANSI',
            'level' => 'N/A',
        ]);

        // Usuarios responsables de área
        $responsableRoleId = DB::table('roles')->where('name', 'responsable_area')->value('id');
        $areas = ['Matemática', 'Física', 'Química', 'Biología', 'Informática', 'Lengua y Literatura'];
        $area_id = 1;
        foreach ($areas as $area) {
            User::create([
                'name' => 'Responsable ' . $area,
                'email' => strtolower('responsable.' . str_replace(' ', '', $area) . '@ohsansi.edu.bo'),
                'password' => Hash::make('responsable123'),
                'role_id' => $responsableRoleId,
                'is_active' => true,
                'last_name_father' => 'Responsable',
                'last_name_mother' => $area,
                'area_id' => $area_id,
                'user_code' => 'RESP' . str_pad($area_id, 3, '0', STR_PAD_LEFT),
                'school' => 'OHSANSI',
                'level' => 'N/A',
            ]);
            $area_id++;
        }

        // Evaluadores de ejemplo
        $evaluadores = [
            ['name' => 'Dr. Juan Pérez', 'area' => 'Matemática', 'area_id' => 1, 'user_code' => 'EVAL003', 'school' => 'Unidad Educativa Norte', 'level' => 'Secundaria', 'last_name_father' => 'Pérez', 'last_name_mother' => 'Gómez'],
            ['name' => 'Dra. María González', 'area' => 'Física', 'area_id' => 2, 'user_code' => 'EVAL004', 'school' => 'Unidad Educativa Sur', 'level' => 'Primaria', 'last_name_father' => 'González', 'last_name_mother' => 'López'],
            ['name' => 'Ing. Carlos Rodríguez', 'area' => 'Informática', 'area_id' => 3, 'user_code' => 'EVAL005', 'school' => 'Unidad Educativa Este', 'level' => 'Preuniversitario', 'last_name_father' => 'Rodríguez', 'last_name_mother' => 'Martínez'],
            ['name' => 'Lic. Ana Torres', 'area' => 'Química', 'area_id' => 4, 'user_code' => 'EVAL006', 'school' => 'Unidad Educativa Oeste', 'level' => 'Secundaria', 'last_name_father' => 'Torres', 'last_name_mother' => 'Ramírez'],
            ['name' => 'Prof. Luis Morales', 'area' => 'Biología', 'area_id' => 5, 'user_code' => 'EVAL007', 'school' => 'Unidad Educativa Central', 'level' => 'Primaria', 'last_name_father' => 'Morales', 'last_name_mother' => 'Fernández'],
        ];

        $evaluadorRoleId = DB::table('roles')->where('name', 'evaluador')->value('id');
        foreach ($evaluadores as $evaluador) {
            User::create([
                'name' => $evaluador['name'],
                'email' => strtolower(str_replace([' ', '.'], ['', ''], $evaluador['name']) . '@ohsansi.edu.bo'),
                'password' => Hash::make('evaluador123'),
                'role_id' => $evaluadorRoleId,
                'is_active' => true,
                'last_name_father' => $evaluador['last_name_father'],
                'last_name_mother' => $evaluador['last_name_mother'],
                'area_id' => $evaluador['area_id'],
                'user_code' => $evaluador['user_code'],
                'school' => $evaluador['school'],
                'level' => $evaluador['level'],
            ]);
        }

        // Crear coordinador
        $coordinadorRoleId = DB::table('roles')->where('name', 'coordinador')->value('id');
        User::create([
            'name' => 'Coordinador General',
            'email' => 'coordinador@ohsansi.edu.bo',
            'password' => Hash::make('coordinador123'),
            'role_id' => $coordinadorRoleId,
            'is_active' => true,
            'last_name_father' => 'Coordinador',
            'last_name_mother' => 'General',
            'area_id' => 1,
            'user_code' => 'COORD001',
            'school' => 'OHSANSI',
            'level' => 'N/A',
        ]);

        // Nuevos usuarios de ejemplo con campos adicionales
        User::create([
            'name' => 'Juan',
            'last_name_father' => 'Pérez',
            'last_name_mother' => 'Gómez',
            'email' => 'juan@example.com',
            'password' => Hash::make('password123'),
            'role_id' => $evaluadorRoleId,
            'area_id' => 1,
            'user_code' => 'EVAL001',
            'school' => 'Unidad Educativa Central',
            'level' => 'Secundaria',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Ana',
            'last_name_father' => 'Rodríguez',
            'last_name_mother' => 'López',
            'email' => 'ana@example.com',
            'password' => Hash::make('password456'),
            'role_id' => $evaluadorRoleId,
            'area_id' => 2,
            'user_code' => 'EVAL002',
            'school' => 'Unidad Educativa Sur',
            'level' => 'Primaria',
            'is_active' => true,
        ]);
    }
}
