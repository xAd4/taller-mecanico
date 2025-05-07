<?php

namespace Database\Seeders;

use App\Models\Tarea;
use Illuminate\Database\Seeder;

class TareaSeeder extends Seeder {
    public function run() {
        Tarea::factory()->count(120)->create();
    }
}