<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CompetitionCategoryArea;
use App\Models\Competicion;
use App\Models\Categoria;
use App\Models\Area;

class CompetitionCategoryAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $competicion = \App\Models\Competicion::first();
        $categorias = \App\Models\Categoria::all();
        $areas = \App\Models\Area::all();

        if ($competicion && $categorias->count() && $areas->count()) {
            foreach ($categorias as $categoria) {
                foreach ($areas as $area) {
                    \App\Models\CompetitionCategoryArea::create([
                        'competition_id' => $competicion->id,
                        'categoria_id' => $categoria->id,
                        'area_id' => $area->id,
                    ]);
                }
            }
        }
        // Puedes agregar más registros aquí si lo necesitas
    }
}
