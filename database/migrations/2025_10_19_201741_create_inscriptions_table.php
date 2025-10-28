<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained('competicions')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // estudiante
            $table->foreignId('area_id')->constrained()->onDelete('cascade');
            $table->foreignId('categoria_id')->constrained('categorias')->onDelete('cascade');
            $table->string('fase');
            $table->enum('estado', ['pendiente', 'confirmada', 'rechazada'])->default('pendiente');
            $table->text('observaciones')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('grupo_nombre')->nullable(); // Nombre del grupo si es grupal
            $table->timestamps();
            
            // Un olimpista no puede inscribirse dos veces en la misma área y competencia
            $table->unique(['competition_id', 'user_id', 'area_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscriptions');
    }
};