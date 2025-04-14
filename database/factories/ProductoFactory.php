<?php

namespace Database\Factories;

use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductoFactory extends Factory {
    protected $model = Producto::class;

    public function definition() {
        $directory = storage_path('app/public/productos'); // Ruta absoluta
    
        // Crear directorio si no existe
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }
    
        return [
            'categoria_id' => \App\Models\Categoria::inRandomOrder()->first()->id,
            'nombre' => $this->faker->sentence(2),
            'detalles' => $this->faker->paragraph,
            'marca' => $this->faker->company,
            'imagen' => 'productos/' . $this->faker->image(
                dir: $directory, 
                width: 640, 
                height: 480, 
                category: null, 
                fullPath: false
            ),
            'stock' => $this->faker->numberBetween(0, 100),
            'precio' => $this->faker->randomFloat(2, 10, 1000),
            'disponibilidad' => $this->faker->boolean(80)
        ];
    }
}
