<?php
namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PhaseSeeder extends Seeder
{
    public function run(): void
    {

        $area = Area::first();
        if (!$area) {
            $area = Area::create([
                'name' => 'General',
                'description' => 'Área por defecto para fases',
            ]);
        }

        DB::table('phases')->insert([
            [
                'area_id' => $area->id,
                'name' => 'Inscripción',
                'description' => 'Fase de inscripción de participantes',
                'start_date' => '2025-09-25',
                'end_date' => '2025-10-05',
            ],
            [
                'area_id' => $area->id,
                'name' => 'Primera Ronda',
                'description' => 'Evaluación inicial de los participantes',
                'start_date' => '2025-10-10',
                'end_date' => '2025-10-15',
            ],
            [
                'area_id' => $area->id,
                'name' => 'Final',
                'description' => 'Fase final de la competición',
                'start_date' => '2025-10-20',
                'end_date' => '2025-10-25',
            ],
        ]);
    }
}
