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
        Schema::table('notifications', function (Blueprint $table) {
            // Agregar columnas necesarias para el sistema de notificaciones de Laravel
            $table->string('type')->after('id');
            $table->morphs('notifiable');
            $table->text('data')->after('notifiable_type');
            $table->timestamp('read_at')->nullable()->after('data');
            
            // Hacer nullable las columnas antiguas para compatibilidad
            $table->string('titulo')->nullable()->change();
            $table->text('mensaje')->nullable()->change();
            $table->dropColumn(['tipo', 'user_id', 'competicion_id', 'stage_id', 'evaluation_id', 'leida', 'fecha_leida', 'metadata']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn(['type', 'notifiable_type', 'notifiable_id', 'data', 'read_at']);
            
            // Restaurar columnas antiguas
            $table->enum('tipo', [
                'evaluacion_pendiente',
                'etapa_completada', 
                'clasificado',
                'desclasificado',
                'recordatorio',
                'sistema'
            ])->after('mensaje');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('competicion_id')->constrained('competicions')->onDelete('cascade');
            $table->foreignId('stage_id')->nullable()->constrained('stages')->onDelete('cascade');
            $table->foreignId('evaluation_id')->nullable()->constrained()->onDelete('cascade');
            $table->boolean('leida')->default(false);
            $table->timestamp('fecha_leida')->nullable();
            $table->json('metadata')->nullable();
        });
    }
};
