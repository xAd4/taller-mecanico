<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model {
    use HasFactory;

    protected $table = 'clientes';

    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'dni',
        'rut',
        'telefono',
        'domicilio',
    ];

    public function ordenes() { 
        return $this->hasMany(Orden::class, 'cliente_id');
    }

    public function vehiculos() {
        return $this->hasMany(Vehiculo::class, 'cliente_id');
    }
}
