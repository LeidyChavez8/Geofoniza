<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Convertir aforo a VARCHAR temporalmente para evitar errores de conversión
        DB::statement('ALTER TABLE data MODIFY aforo VARCHAR(255)');

        // Reemplazar valores inválidos por NULL
        DB::statement("UPDATE data SET aforo = NULL WHERE aforo NOT REGEXP '^[0-9]+$'");

        // Convertir aforo de nuevo a INTEGER y permitir valores NULL
        Schema::table('data', function (Blueprint $table) {
            $table->integer('aforo')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('data', function (Blueprint $table) {
            $table->integer('aforo')->nullable(false)->change();
        });
    }
};