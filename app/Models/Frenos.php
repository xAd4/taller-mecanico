<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Frenos extends Model {
    
    use HasFactory;

    protected $fillable = [
        "tarea_id",
        "delanteros",
        "traseros",
        "estacionamiento",
        "numero_cricket",
    ];

    public function tarea() {
        return $this->belongsTo(Tarea::class);
    }

}
