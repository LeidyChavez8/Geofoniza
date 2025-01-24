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
        Schema::create('data', function (Blueprint $table) {
            $table->id();
            $table->string('contrato')->nullable();
            $table->string('producto')->nullable();
            $table->string('nombres')->nullable();
            $table->string('calificacion')->nullable();
            $table->string('categoria')->nullable();
            $table->string('direccion')->nullable();
            $table->string('ubicacion')->nullable();
            $table->string('medidor')->nullable();
            $table->string('orden')->nullable();
            $table->string('lectura_anterior')->nullable();
            $table->string('fecha_lectura_anterior')->nullable();
            $table->string('observacion_lectura_anterior')->nullable();
            $table->string('ciclo')->nullable();
            $table->string('recorrido')->nullable();
            $table->string('lectura')->nullable();
            $table->string('observacion_inspeccion')->nullable();
            $table->string('url_foto')->nullable();
            $table->text('firma')->nullable();
            $table->foreignId('id_user')->nullable()->constrained('users');
            $table->string('estado')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data');
    }
};
