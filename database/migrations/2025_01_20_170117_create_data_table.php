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
            $table->string('nombre_cliente');
            $table->string('email')->nullable();
            $table->string('telefono')->nullable();
            $table->text('firma')->nullable();
            $table->string('cuenta');
            $table->string('direccion');
            $table->string('recorrido');
            $table->string('ciclo');
            $table->string('medidor');
            $table->string('año');
            $table->string('mes');
            $table->string('periodo');
            $table->foreignId('id_operario')->nullable()->constrained('users');
            $table->tinyInteger('estado')->default(0)->nullable();
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
