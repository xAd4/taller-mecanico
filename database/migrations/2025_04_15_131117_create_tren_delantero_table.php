<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() {
        Schema::create('tren_delantero', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tarea_id')->constrained('tareas')->onDelete('cascade');
            $table->boolean('conv')->default(false);
            $table->boolean('comba')->default(false);
            $table->boolean('avance')->default(false);
            $table->boolean('rotulas')->default(false);
            $table->boolean('punteros')->default(false);
            $table->boolean('bujes')->default(false);
            $table->boolean('caja_direccion')->default(false);
            $table->boolean('amort')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tren_delantero');
    }
};
