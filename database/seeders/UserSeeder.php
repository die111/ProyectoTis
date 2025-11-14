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
            'ci' => '12345678',
            'address' => 'Av. Principal 123',
            'telephone_number' => '78945612',
            'date_of_birth' => '1980-01-01',
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
                'ci' => 'RESPCI' . $area_id,
                'address' => 'Zona ' . $area,
                'telephone_number' => '7000000' . $area_id,
                'date_of_birth' => '1985-01-0' . $area_id,
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
                'ci' => 'EVALCI' . $evaluador['area_id'],
                'address' => 'Calle ' . $evaluador['area'],
                'telephone_number' => '6000000' . $evaluador['area_id'],
                'date_of_birth' => '1990-01-0' . $evaluador['area_id'],
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
            'ci' => '87654321',
            'address' => 'Av. Secundaria 456',
            'telephone_number' => '78912345',
            'date_of_birth' => '1982-05-10',
        ]);

        // Nuevos usuarios de ejemplo with additional fields
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
            'ci' => '11111111',
            'address' => 'Calle Central 1',
            'telephone_number' => '70011111',
            'date_of_birth' => '1995-03-15',
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
            'ci' => '22222222',
            'address' => 'Calle Sur 2',
            'telephone_number' => '70022222',
            'date_of_birth' => '1997-07-20',
        ]);

        // Usuarios estudiantes de ejemplo
        // $estudianteRoleId = DB::table('roles')->where('name', 'estudiante')->value('id');
        // for ($i = 1; $i <= 50; $i++) {
        //     $dia = ($i % 28) + 1; // Día entre 1 y 28
        //     User::create([
        //         'name' => 'Estudiante' . $i,
        //         'last_name_father' => 'ApellidoP' . $i,
        //         'last_name_mother' => 'ApellidoM' . $i,
        //         'email' => 'estudiante' . $i . '@example.com',
        //         'password' => Hash::make('estudiante123'),
        //         'role_id' => $estudianteRoleId,
        //         'area_id' => ($i % 6) + 1, // Asignar áreas del 1 al 6
        //         'user_code' => 'ESTU' . str_pad($i, 3, '0', STR_PAD_LEFT),
        //         'school' => 'Unidad Educativa Ejemplo',
        //         'level' => 'Secundaria',
        //         'is_active' => true,
        //         'ci' => 'ESTUCI' . $i,
        //         'address' => 'Dirección ' . $i,
        //         'telephone_number' => '7100000' . $i,
        //         'date_of_birth' => '2005-01-' . str_pad($dia, 2, '0', STR_PAD_LEFT),
        //     ]);
        // }
    }
}
