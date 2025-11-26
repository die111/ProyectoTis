<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;

class InscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('inscriptions')->truncate();

        $categoriaIds = DB::table('categorias')->pluck('id')->toArray();
        $areaIds = DB::table('areas')->pluck('id')->toArray();
        $students = User::whereHas('role', function($q){ $q->where('name', 'estudiante'); })->limit(10)->get();
        $competitionId = 1;
        $faseId = 1;

        foreach ($students as $student) {
            DB::table('inscriptions')->insert([
                'competition_id' => $competitionId,
                'user_id' => $student->id,
                'area_id' => $student->area_id,
                'categoria_id' => $categoriaIds[array_rand($categoriaIds)],
                'fase' => 1,
                'estado' => 'pendiente',
                'observaciones' => null,
                'is_active' => true,
                'name_grupo' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
