<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrenDelantero extends Model {

    use HasFactory;

    protected $table = 'tren_delantero';

    protected $fillable = [
        'tarea_id',
        'conv',
        'comba',
        'avance', 
        'rotulas',
        'punteros',
        'bujes',
        'caja_direccion',
        'conv2',
        'comba2',
        'avance2',
        'amort',
    ];

    public function tarea() {
        return $this->belongsTo(Tarea::class);
    }
}