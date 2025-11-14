<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluation_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_id')->constrained()->onDelete('cascade');
            $table->decimal('nota_anterior', 5, 2)->nullable();
            $table->decimal('nota_nueva', 5, 2)->nullable();
            $table->string('estado_anterior')->nullable();
            $table->string('estado_nuevo')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Quién hizo 
                                                                             //   el cambio
            $table->text('motivo'); // Motivo del cambio
            $table->timestamps();
            
            $table->index('evaluation_id');
            $table->index('user_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluation_logs');
    }
};