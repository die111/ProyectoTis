<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Competicion;

class CompeticionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Competicion::create([
            'name' => 'Comp de prueba 2025',
            'description' => 'esta es una comp de prueba creada con los seeders para ver si funciona las fechas talves fallen',
            'state' => 'activa',
            'fechaInicio' => '2025-12-01 08:00:00',
            'fechaFin' => '2025-12-31 18:00:00',
            'inscripcion_inicio' => '2025-12-02',
            'inscripcion_fin' => '2025-12-05',
            'evaluacion_inicio' => '2025-12-06',
            'evaluacion_fin' => '2025-12-29',
            'premiacion_inicio' => '2025-12-30',
            'premiacion_fin' => '2025-12-31',
        ]);
        // Puedes agregar más registros aquí si lo necesitas
    }
}
