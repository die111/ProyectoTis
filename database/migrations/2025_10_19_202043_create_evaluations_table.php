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
            $table->foreignId('stage_id')->nullable()->constrained('stages')->onDelete('set null');
            $table->foreignId('evaluator_id')->constrained('users')->onDelete('restrict'); // Usuario como evaluador
            $table->decimal('nota', 5, 2); // Nota con decimales
            $table->decimal('promedio', 5, 2)->default(0.00); // Promedio del grupo
            $table->enum('estado', ['clasificado', 'no_clasificado', 'desclasificado']);
            $table->text('observaciones_evaluador')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['inscription_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};