<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
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
        Schema::dropIfExists('vehiculos');
    }
};
