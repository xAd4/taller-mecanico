<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrenDelantero extends Model {

    use HasFactory;

    protected $fillable = [
        'tarea_id',
        'conv',
        'comba',
        'avance', 
        'rotulas',
        'punteros',
        'bujes',
        'caja_direccion',
        'amort'
    ];

    public function tarea() {
        return $this->belongsTo(Tarea::class);
    }
}