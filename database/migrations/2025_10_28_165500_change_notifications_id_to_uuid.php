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
        // Primero, eliminar todas las notificaciones existentes para evitar conflictos
        DB::table('notifications')->truncate();
        
        // Cambiar el tipo de columna id de bigint a uuid
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn('id');
        });
        
        Schema::table('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary()->first();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn('id');
        });
        
        Schema::table('notifications', function (Blueprint $table) {
            $table->id()->first();
        });
    }
};
