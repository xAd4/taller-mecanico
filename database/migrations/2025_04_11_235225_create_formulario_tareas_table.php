<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {
        Schema::create('tareas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orden_id')->constrained('ordenes')->onDelete('cascade');
            $table->foreignId('mecanico_id')->constrained('users')->onDelete('cascade');
            $table->enum('estado_de_trabajo', ['pendiente', 'en_proceso', 'completado'])->default('pendiente');
            $table->decimal('precio_de_trabajo', 10, 2)->default(0);
            $table->text('detalles')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('tareas');
    }
};
