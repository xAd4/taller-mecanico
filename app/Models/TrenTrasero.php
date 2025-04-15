<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrenTrasero extends Model {
    use HasFactory;

    protected $fillable = [
        "tarea_id",
        "conv",
        "comba",
        "brazos_susp",
        "articulaciones",
        "amort",
    ];

    public function tarea(){
        return $this->belongsTo(Tarea::class);
    }
}
