<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inscription_id')->constrained()->onDelete('cascade');
            $table->foreignId('stage_id')->constrained('stages')->onDelete('cascade');
            $table->foreignId('evaluator_id')->constrained('users')->onDelete('cascade'); // Usuario como evaluador
            $table->decimal('nota', 5, 2); // Nota con decimales
            $table->enum('estado', ['clasificado', 'no_clasificado', 'desclasificado']);
            $table->text('observaciones_evaluador')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Un olimpista solo puede ser evaluado una vez por etapa
            $table->unique(['inscription_id', 'stage_id']);
            
            $table->index(['stage_id', 'estado']);
            $table->index(['inscription_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};