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
                    'name' => 'Fase Inicial',
                    'description' => 'Competicion general',
                    'clasificados' => 100,
                    'is_active' => true,
                ],
                [
                    'name' => 'Fase 1',
                    'description' => 'Primera fase de la competicion',
                    'clasificados' => 100,
                    'is_active' => true,
                ],
                [
                    'name' => 'Fase 2',
                    'description' => 'Segunda fase de la competcion',
                    'clasificados' => 100,
                    'is_active' => true,
                ],
                [
                    'name' => 'Fase Final',
                    'description' => 'Ganadores de la competencia',
                    'clasificados' => 100,
                    'is_active' => true,
                ],
            ]);
    }
}
