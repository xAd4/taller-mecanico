<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {
        Schema::create('tareas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orden_id')->constrained('ordens')->onDelete('cascade');
            $table->foreignId('mecanico_id')->constrained('users')->onDelete('cascade');
            $table->enum('estado_de_trabajo', ['pendiente', 'en_proceso', 'completado'])->default('pendiente');
            $table->decimal('precio_de_trabajo', 10, 2)->default(0);
            $table->text('detalles_de_tarea')->nullable();
            $table->text('notificacion_al_cliente')->nullable();
            $table->foreignId('tren_delantero_id')->nullable()->constrained('tren_delantero')->onDelete('cascade');
            $table->foreignId('tren_trasero_id')->nullable()->constrained('tren_trasero')->onDelete('cascade');
            $table->foreignId('freno_id')->nullable()->constrained('frenos')->onDelete('cascade');
            $table->foreignId('estado_neumatico_id')->nullable()->constrained('estado_neumaticos')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('tareas');
    }
};
