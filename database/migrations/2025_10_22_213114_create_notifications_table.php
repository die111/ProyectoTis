<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('mensaje');
            $table->enum('tipo', [
                'evaluacion_pendiente',
                'etapa_completada', 
                'clasificado',
                'desclasificado',
                'recordatorio',
                'sistema'
            ]);
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Destinatario
            $table->foreignId('competicion_id')->constrained('competicions')->onDelete('cascade');
            $table->foreignId('stage_id')->nullable()->constrained('stages')->onDelete('cascade');
            $table->foreignId('evaluation_id')->nullable()->constrained()->onDelete('cascade');
            $table->boolean('leida')->default(false);
            $table->timestamp('fecha_leida')->nullable();
            $table->json('metadata')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['user_id', 'leida']);
            $table->index(['competicion_id', 'tipo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};