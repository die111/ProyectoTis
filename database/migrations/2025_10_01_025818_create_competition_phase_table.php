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
        Schema::create('competition_phase', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained('competicions')->onDelete('cascade');
            $table->foreignId('phase_id')->constrained('phases')->onDelete('cascade');
            $table->integer('clasificados')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('color')->nullable();
            // Nuevos campos de clasificación incorporados en la creación
            $table->string('classification_type')->nullable();
            $table->unsignedInteger('classification_cupo')->nullable();
            $table->decimal('classification_nota_minima', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competition_phase');
    }
};
