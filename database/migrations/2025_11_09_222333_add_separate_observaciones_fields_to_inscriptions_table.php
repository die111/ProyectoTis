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
        Schema::table('inscriptions', function (Blueprint $table) {
            // Renombrar 'observaciones' a 'observaciones_estudiante'
            $table->renameColumn('observaciones', 'observaciones_estudiante');
            
            // Agregar columna para el motivo de rechazo del administrador
            $table->text('motivo_rechazo')->nullable()->after('observaciones_estudiante');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inscriptions', function (Blueprint $table) {
            // Eliminar la columna de motivo de rechazo
            $table->dropColumn('motivo_rechazo');
            
            // Renombrar de vuelta a 'observaciones'
            $table->renameColumn('observaciones_estudiante', 'observaciones');
        });
    }
};
