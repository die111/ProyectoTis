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
        Schema::create('competicions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('state', ['borrador','activa','completada','cancelada'])
                  ->default('borrador');
            $table->dateTime('fechaInicio');
            $table->dateTime('fechaFin');
            
            // Fechas de Etapa de Inscripción
            $table->date('inscripcion_inicio')->nullable();
            $table->date('inscripcion_fin')->nullable();
            
            // Fechas de Etapa de Evaluación
            $table->date('evaluacion_inicio')->nullable();
            $table->date('evaluacion_fin')->nullable();
            
            // Fechas de Etapa de Premiación
            $table->date('premiacion_inicio')->nullable();
            $table->date('premiacion_fin')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competicions');
    }
};