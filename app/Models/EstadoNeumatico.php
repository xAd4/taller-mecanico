<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoNeumatico extends Model {
    
    use HasFactory;

    protected $fillable = [
        'tarea_id',
        'delanteros_derechos',
        'delanteros_izquierdos',
        'traseros_derechos',
        'traseros_izquierdos',
    ];

    public function tarea() {
        return $this->belongsTo(Tarea::class);
    }
}
