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
        Schema::create('stages', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->dateTime('fechaInicio');
            $table->dateTime('fechaFin');
            $table->unsignedBigInteger('id_competition');
            $table->timestamps();
            
            $table->foreign('id_competition')->references('id')->on('competicions')->onDelete('cascade');
            
            $table->index('nombre');
            $table->index('id_competition');
            $table->index(['fechaInicio', 'fechaFin']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stages');
    }
};