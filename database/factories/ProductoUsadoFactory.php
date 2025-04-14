<?php

namespace Database\Factories;

use App\Models\ProductoUsado;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductoUsadoFactory extends Factory {
    protected $model = ProductoUsado::class;

    public function definition() {
        return [
            'tarea_id' => \App\Models\Tarea::inRandomOrder()->first()->id,
            'producto_id' => \App\Models\Producto::inRandomOrder()->first()->id,
            'cantidad' => $this->faker->numberBetween(1, 5)
        ];
    }
}
