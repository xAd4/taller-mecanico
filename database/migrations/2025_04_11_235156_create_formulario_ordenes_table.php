<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {
        Schema::create('ordens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->foreignId('vehiculo_id')->constrained('vehiculos')->onDelete('cascade');
            $table->text('detalle_de_trabajos_a_realizar')->nullable();
            $table->date('recepcion');
            $table->date('prometido')->nullable();
            $table->boolean('cambio_de_aceite')->nullable()->default(false);
            $table->boolean('cambio_de_filtro')->nullable()->default(false);
            $table->text('detalles_de_entrada_del_vehiculo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordenes');
    }
};
