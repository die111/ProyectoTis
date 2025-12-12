<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Agrega límites explícitos a campos string que no los tenían
     */
    public function up(): void
    {
        // Limitar campos en users
        Schema::table('users', function (Blueprint $table) {
            $table->string('name', 255)->change();
            $table->string('last_name_father', 255)->nullable()->change();
            $table->string('last_name_mother', 255)->nullable()->change();
            $table->string('user_code', 255)->change();
            $table->string('school', 255)->nullable()->change();
            $table->string('level', 50)->nullable()->change();
            $table->string('profile_photo', 500)->nullable()->change();
            $table->string('ci', 20)->nullable()->change();
            $table->string('address', 500)->nullable()->change();
            $table->string('telephone_number', 20)->nullable()->change();
        });

        // Limitar campos en areas
        Schema::table('areas', function (Blueprint $table) {
            $table->string('name', 255)->change();
        });

        // Limitar campos en phases
        Schema::table('phases', function (Blueprint $table) {
            $table->string('name', 50)->change();
        });

        // Limitar campos en competicions
        Schema::table('competicions', function (Blueprint $table) {
            $table->string('name', 255)->change();
        });

        // Limitar campos en stages
        Schema::table('stages', function (Blueprint $table) {
            $table->string('nombre', 100)->change();
        });

        // Limitar campos en categorias
        Schema::table('categorias', function (Blueprint $table) {
            $table->string('nombre', 100)->change();
        });

        // Limitar campos en inscriptions
        Schema::table('inscriptions', function (Blueprint $table) {
            $table->string('name_grupo', 255)->change();
            $table->string('fase', 50)->change();
        });

        // Limitar campos en notifications
        // NOTA: La columna 'titulo' fue eliminada en migration 2025_10_28_163617
        // Schema::table('notifications', function (Blueprint $table) {
        //     $table->string('titulo', 255)->change();
        // });

        // Limitar campos en password_reset_tokens
        Schema::table('password_reset_tokens', function (Blueprint $table) {
            $table->string('token', 500)->change();
        });

        // Limitar campos en reclamos
        Schema::table('reclamos', function (Blueprint $table) {
            $table->string('estado', 50)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir a string sin límite (no recomendado pero incluido para rollback)
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->change();
            $table->string('last_name_father')->nullable()->change();
            $table->string('last_name_mother')->nullable()->change();
            $table->string('user_code')->change();
            $table->string('school')->nullable()->change();
            $table->string('level')->nullable()->change();
            $table->string('profile_photo')->nullable()->change();
            $table->string('ci')->nullable()->change();
            $table->string('address')->nullable()->change();
            $table->string('telephone_number')->nullable()->change();
        });

        Schema::table('areas', function (Blueprint $table) {
            $table->string('name')->change();
        });

        Schema::table('phases', function (Blueprint $table) {
            $table->string('name')->change();
        });

        Schema::table('competicions', function (Blueprint $table) {
            $table->string('name')->change();
        });

        Schema::table('stages', function (Blueprint $table) {
            $table->string('nombre')->change();
        });

        Schema::table('categorias', function (Blueprint $table) {
            $table->string('nombre')->change();
        });

        Schema::table('inscriptions', function (Blueprint $table) {
            $table->string('name_grupo')->change();
            $table->string('fase')->change();
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->string('titulo')->change();
        });

        Schema::table('password_reset_tokens', function (Blueprint $table) {
            $table->string('token')->change();
        });

        Schema::table('reclamos', function (Blueprint $table) {
            $table->string('estado')->change();
        });
    }
};
