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
        // Postgres: eliminar la restricción NOT NULL de la columna
        DB::statement('ALTER TABLE users ALTER COLUMN area_id DROP NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restaurar NOT NULL (esto fallará si existen filas con NULL en area_id).
        DB::statement('ALTER TABLE users ALTER COLUMN area_id SET NOT NULL');
    }
};
