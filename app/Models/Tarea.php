<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarea extends Model {
    use HasFactory;

    protected $fillable = [
        'orden_id',
        'mecanico_id',
        'estado_de_trabajo',
        'detalles_de_tarea',
        'notificacion_al_cliente',
    ];

    
    public function trenDelantero() {
        return $this->hasOne(TrenDelantero::class);
    }

    public function trenTrasero() {
        return $this->hasOne(TrenTrasero::class);
    }

    public function frenos() {
        return $this->hasOne(Frenos::class);
    }

    public function estadoNeumaticos() {
        return $this->hasOne(EstadoNeumatico::class);
    }

    public function orden() {
        return $this->belongsTo(Orden::class);
    }

    public function productosUsados() {
        return $this->hasMany(ProductoUsado::class);
    }

    public function mecanico() {
        return $this->belongsTo(User::class, 'mecanico_id');
    }

    public function getTotalMateriales() {
        return $this->productosUsados->sum(function ($productoUsado) {
            return $productoUsado->calcularTotal();
        });
    }
    
    public function getPrecioTotalAttribute() {
        return $this->getTotalMateriales() + $this->precio_de_trabajo;
    }
}
