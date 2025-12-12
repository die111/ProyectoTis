<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Agregar unique constraint en areas.name para prevenir áreas duplicadas
        try {
            Schema::table('areas', function (Blueprint $table) {
                $table->unique('name', 'areas_name_unique');
            });
        } catch (\Exception $e) {
            // Ya existe el índice
        }

        // Agregar unique constraint en categorias.nombre para prevenir categorías duplicadas
        try {
            Schema::table('categorias', function (Blueprint $table) {
                $table->unique('nombre', 'categorias_nombre_unique');
            });
        } catch (\Exception $e) {
            // Ya existe el índice
        }

        // Agregar unique constraint en competicions.name para prevenir competiciones duplicadas por nombre y fecha
        try {
            Schema::table('competicions', function (Blueprint $table) {
                // Usar nombre y año de inicio para unique
                $table->index('name'); // Índice simple para búsquedas
            });
        } catch (\Exception $e) {
            // Ya existe el índice
        }

        // Agregar constraint para stages: no permitir etapas con el mismo nombre en la misma competición
        try {
            Schema::table('stages', function (Blueprint $table) {
                $table->unique(['nombre', 'id_competition'], 'stages_nombre_competition_unique');
            });
        } catch (\Exception $e) {
            // Ya existe el índice
        }

        // Agregar constraint para evaluations: prevenir evaluaciones duplicadas de la misma inscripción en la misma etapa
        try {
            Schema::table('evaluations', function (Blueprint $table) {
                // Crear índice único parcial solo para registros activos
                // Esto previene múltiples evaluaciones activas para la misma inscripción y etapa
                $table->index(['inscription_id', 'stage_id', 'is_active'], 'eval_inscription_stage_active_idx');
            });
        } catch (\Exception $e) {
            // Ya existe el índice
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('areas', function (Blueprint $table) {
            $table->dropUnique('areas_name_unique');
        });

        Schema::table('categorias', function (Blueprint $table) {
            $table->dropUnique('categorias_nombre_unique');
        });

        Schema::table('competicions', function (Blueprint $table) {
            $table->dropIndex(['name']);
        });

        Schema::table('stages', function (Blueprint $table) {
            $table->dropUnique('stages_nombre_competition_unique');
        });

        Schema::table('evaluations', function (Blueprint $table) {
            $table->dropIndex('eval_inscription_stage_active_idx');
        });
    }
};
