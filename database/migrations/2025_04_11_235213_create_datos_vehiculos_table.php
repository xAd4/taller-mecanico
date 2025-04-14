<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('datos_vehiculos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('datos_cliente_id')->constrained('datos_clientes')->onDelete('cascade');
            $table->string('modelo');
            $table->string('marca');
            $table->string('color');
            $table->string('kilometraje');
            $table->string('numero_de_serie')->nullable();
            $table->string('numero_de_motor')->nullable();
            $table->date('fecha_de_compra')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('datos_vehiculos');
    }
};
