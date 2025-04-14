<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model {
    use HasFactory;

    protected $table = 'vehiculos';

    protected $fillable = [
        'cliente_id',
        'modelo',
        'color',
        'matricula',
        'kilometraje',
        'numero_de_serie',
        'numero_de_motor',
        'fecha_de_compra',
    ];

    public function cliente() {
        return $this->belongsTo(Cliente::class);
    }
}
