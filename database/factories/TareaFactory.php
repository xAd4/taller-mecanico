<?php

namespace Database\Factories;

use App\Models\Tarea;
use Illuminate\Database\Eloquent\Factories\Factory;

class TareaFactory extends Factory {
    protected $model = Tarea::class;

    public function definition() {
        return [
            'orden_id' => \App\Models\Orden::inRandomOrder()->first()->id,
            'mecanico_id' => \App\Models\User::where('rol', 'mecanico')->inRandomOrder()->first()->id,
            'estado_de_trabajo' => $this->faker->randomElement(['pendiente', 'en_proceso', 'completado']),
            'precio_de_trabajo' => $this->faker->randomFloat(2, 50, 1000),
            'detalles' => $this->faker->paragraph
        ];
    }
}
