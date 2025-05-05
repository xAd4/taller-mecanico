<?php

namespace App\Observers;

use App\Models\EstadoNeumatico;
use App\Models\Frenos;
use App\Models\ProductoUsado;
use App\Models\Tarea;
use App\Models\TrenDelantero;
use App\Models\TrenTrasero;

class TareaObserver {
    /**
     * Al crearse una instancia de tarea, se crea automáticamente las instancias relacionadas.
     */
    public function created(Tarea $tarea): void {
        $tarea->trenDelantero()->create([]);
        $tarea->trenTrasero()->create([]);
        $tarea->frenos()->create([]);
        $tarea->estadoNeumaticos()->create([]);
        $tarea->productosUsados()->create([]);
    }
}
