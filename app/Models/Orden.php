<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orden extends Model {
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'datos_extras',
        'recepcion',
        'prometido',
        'cambio_de_aceite',
        'cambio_de_filtro',
        'detalles',
    ];

    public function cliente() {
        return $this->belongsTo(Cliente::class);
    }
}
