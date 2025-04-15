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
        Schema::create('estado_neumaticos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tarea_id')->constrained('tareas')->onDelete('cascade');
            $table->boolean('delanteros_derechos')->default(false);
            $table->boolean('delanteros_izquierdos')->default(false);  
            $table->boolean('traseros_derechos')->default(false);  
            $table->boolean('traseros_izquierdos')->default(false);  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estado_neumaticos');
    }
};
