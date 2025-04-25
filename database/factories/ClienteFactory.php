<?php

namespace Database\Factories;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClienteFactory extends Factory {
    protected $model = Cliente::class;

    public function definition() {
        return [
            'nombre' => $this->faker->firstName,
            'email' => $this->faker->unique()->safeEmail,
            'rut' => $this->faker->unique()->numerify('########'),
            'telefono' => $this->faker->phoneNumber,
            'domicilio' => $this->faker->address
        ];
    }
}
