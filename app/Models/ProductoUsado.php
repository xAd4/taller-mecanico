<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductoUsado extends Model {
    use HasFactory;

    protected $fillable = [
        'tarea_id',
        'producto_id',
        'cantidad_usada',
    ];

    public function tarea() {
        return $this->belongsTo(Tarea::class);
    }

    public function producto() {
        return $this->belongsTo(Producto::class);
    }

    public function calcularTotal() {
        return $this->cantidadUsada * $this->producto->precio;
    }
}
