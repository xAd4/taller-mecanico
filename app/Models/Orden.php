<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orden extends Model {
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'vehiculo_id',
        'detalle_de_trabajos_a_realizar',
        'recepcion',
        'prometido',
        'cambio_de_aceite',
        'cambio_de_filtro',
        'detalles_de_entrada_del_vehiculo',
        'disponible',
    ];

    public function cliente() {
        return $this->belongsTo(Cliente::class);
    }

    public function vehiculo() {
        return $this->belongsTo(Vehiculo::class);
    }

    public function tareas(){
        return $this->hasMany(Tarea::class);
    }
}
