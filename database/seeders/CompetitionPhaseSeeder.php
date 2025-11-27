<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompetitionPhaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('competition_phase')->insert([
            [
                'competition_id' => 1,
                'phase_id' => 1,
                'clasificados' => 0,
                'start_date' => '2025-12-06',
                'end_date' => '2025-12-20',
                'color' => '#405f91',
                'classification_type' => 'notas_altas',
                'classification_cupo' => null,
                'classification_nota_minima' => 80.00,
                'created_at' => '2025-11-27 19:45:55',
                'updated_at' => '2025-11-27 19:45:55',
            ],
            [
                'competition_id' => 1,
                'phase_id' => 2,
                'clasificados' => 2,
                'start_date' => '2025-12-21',
                'end_date' => '2025-12-29',
                'color' => '#1cb32b',
                'classification_type' => 'cupo',
                'classification_cupo' => 5,
                'classification_nota_minima' => null,
                'created_at' => '2025-11-27 19:45:55',
                'updated_at' => '2025-11-27 19:45:55',
            ],
        ]);
    }
}
