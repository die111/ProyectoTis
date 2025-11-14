<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained('competicions')->onDelete('cascade');
            $table->foreignId('area_id')->constrained()->onDelete('cascade');
            $table->foreignId('level_id')->constrained('levels')->onDelete('cascade');
            $table->integer('oro')->default(1);
            $table->integer('plata')->default(1);
            $table->integer('bronce')->default(1);
            $table->integer('menciones_honor')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Configuración única por competencia, área y nivel
            $table->unique(['competition_id', 'area_id', 'level_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medals');
    }
};