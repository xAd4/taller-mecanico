<?php

namespace Database\Factories;

use App\Models\Vehiculo;
use Illuminate\Database\Eloquent\Factories\Factory;

class VehiculoFactory extends Factory
{
    protected $model = Vehiculo::class;

    public function definition()
    {
        return [
            'modelo' => $this->faker->word, 
            'marca' => $this->faker->company,
            'color' => $this->faker->colorName,
            'matricula' => $this->faker->unique()->regexify('[A-Z]{2}-[0-9]{4}'),
            'kilometraje' => $this->faker->numberBetween(0, 200000),
            'numero_de_serie' => $this->faker->regexify('[A-HJ-NPR-Z0-9]{17}'),
            'numero_de_motor' => $this->faker->regexify('[A-Z0-9]{10}'),
            'fecha_de_compra' => $this->faker->date,
            'disponible' => $this->faker->boolean(90),
        ];
    }
}
