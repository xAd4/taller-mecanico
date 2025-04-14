<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            CategoriaSeeder::class,
            ProductoSeeder::class,
            UserSeeder::class,
            ClienteSeeder::class,
            VehiculoSeeder::class,
            OrdenSeeder::class,
            TareaSeeder::class,
            ProductoUsadoSeeder::class
        ]);
    }
}
