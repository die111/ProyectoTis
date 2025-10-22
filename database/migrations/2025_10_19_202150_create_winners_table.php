<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('winners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_id')->constrained()->onDelete('cascade');
            $table->enum('premio', ['oro', 'plata', 'bronce', 'mencion_honor'])->nullable();
            $table->integer('posicion')->nullable(); // Posición en el ranking
            $table->text('observaciones')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['evaluation_id', 'premio']);
            $table->index('posicion');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('winners');
    }
};