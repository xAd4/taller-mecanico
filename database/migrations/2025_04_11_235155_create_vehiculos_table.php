<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id();
            $table->string('modelo');
            $table->string('marca');
            $table->string('color');
            $table->string('matricula')->unique();
            $table->string('kilometraje');
            $table->string('numero_de_serie')->nullable();
            $table->string('numero_de_motor')->nullable();
            $table->date('fecha_de_compra')->nullable();
            $table->boolean('disponible')->nullable()->default(true);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('vehiculos');
    }
};
