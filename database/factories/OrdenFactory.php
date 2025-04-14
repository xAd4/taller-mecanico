<?php

namespace Database\Factories;

use App\Models\Orden;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrdenFactory extends Factory {
    protected $model = Orden::class;

    public function definition() {
        return [
            'cliente_id' => \App\Models\Cliente::inRandomOrder()->first()->id,
            'datos_extras' => $this->faker->sentence,
            'recepcion' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'prometido' => $this->faker->dateTimeBetween('now', '+1 month'),
            'cambio_de_aceite' => $this->faker->boolean,
            'cambio_de_filtro' => $this->faker->boolean,
            'detalles' => $this->faker->paragraph
        ];
    }
}
