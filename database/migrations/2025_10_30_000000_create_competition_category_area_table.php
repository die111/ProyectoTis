<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competition_category_area', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained('competicions')->onDelete('cascade');
            $table->foreignId('categoria_id')->constrained('categorias')->onDelete('cascade');
            $table->foreignId('area_id')->constrained('areas')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['competition_id', 'categoria_id', 'area_id'], 'cca_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competition_category_area');
    }
};
