<?php

namespace Database\Seeders;

use App\Models\ProductoUsado;
use Illuminate\Database\Seeder;

class ProductoUsadoSeeder extends Seeder {
    public function run() {
        ProductoUsado::factory()->count(100)->create();
    }
}