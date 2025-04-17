<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('producto_usados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tarea_id')->constrained('tareas')->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            $table->integer('cantidad')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('producto_usados');
    }
};
