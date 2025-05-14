<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder {
    public function run() {
        // Usuario Jefe
        User::create([
            'name' => 'Jefe Taller',
            'email' => 'jefe@taller.com',
            'password' => Hash::make('password'),
            'rol' => User::ROL_JEFE
        ]);
    }
}