<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->boolean('primero')->default(false);
            $table->boolean('segundo')->default(false);
            $table->boolean('tercero')->default(false);
            $table->boolean('cuarto')->default(false);
            $table->boolean('quinto')->default(false);
            $table->boolean('sexto')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Asegurar que ninguna FK desde competitionables bloquee el drop de categorias
        // (este constraint puede existir por una migración faltante en el repositorio)
        DB::statement('ALTER TABLE competitionables DROP CONSTRAINT IF EXISTS competitionables_categoria_id_foreign');
        DB::statement('ALTER TABLE competitionables DROP COLUMN IF EXISTS categoria_id');

        // Eliminar primero las tablas hijas que dependen de categorias
        Schema::dropIfExists('competition_category_area');
        Schema::dropIfExists('inscriptions');

        // Ahora eliminar la tabla categorias
        Schema::dropIfExists('categorias');
    }
};
