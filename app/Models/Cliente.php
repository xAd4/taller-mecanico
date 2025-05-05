<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model {
    use HasFactory;

    protected $table = 'clientes';

    protected $fillable = [
        'nombre',
        'email',
        'rut',
        'telefono',
        'domicilio',
        'disponible',
    ];

    public function ordenes() { 
        return $this->hasMany(Orden::class, 'cliente_id');
    }
}
