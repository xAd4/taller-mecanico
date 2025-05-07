<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Orden;
use App\Models\Vehiculo;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class BuscadorController extends Controller {
    // Colecciones permitidas
    private const ALLOWED_COLLECTIONS = ['clientes', 'ordenes'];

    // Búsqueda general
    public function search(string $collection, string $term): JsonResponse {
        try {
            // Validar colección permitida
            if (!in_array($collection, self::ALLOWED_COLLECTIONS)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Colecciones permitidas: ' . implode(', ', self::ALLOWED_COLLECTIONS)
                ], 400);
            }

            // Redirigir a la búsqueda específica
            return $this->{"search" . ucfirst($collection)}($term);
            
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error en la búsqueda',
                'error' => config('app.debug') ? $th->getMessage() : null
            ], 500);
        }
    }

    // Búsqueda de clientes
    private function searchClientes(string $term): JsonResponse {
        $isNumericId = is_numeric($term);
        
        $query = Cliente::query()->with('ordenes');

        if ($isNumericId) {
            $query->where('id', $term);
        } else {
            $query->where(function($q) use ($term) {
                $q->where(DB::raw("CONCAT(nombre, ' ', apellido)"), 'LIKE', "%{$term}%")
                  ->orWhere('email', 'LIKE', "%{$term}%")
                  ->orWhere('dni', 'LIKE', "%{$term}%");
            });
        }

        $results = $query->get();

        return response()->json([
            'status' => true,
            'data' => ['results' => $results],
            'message' => 'Búsqueda de clientes realizada correctamente'
        ]);
    }

    private function searchOrdenes(string $term): JsonResponse
{
    try {
        // Normalizar el término de búsqueda eliminando guiones
        $normalizedTerm = str_replace('-', '', $term);

        $query = Orden::with(['cliente', 'vehiculo', 'tareas']);

        if (is_numeric($normalizedTerm)) {
            $query->where('id', $normalizedTerm);
        } else {
            // Búsqueda por matrícula del vehículo asociado
            $query->whereHas('vehiculo', function ($q) use ($normalizedTerm) {
                $q->where(DB::raw("REPLACE(matricula, '-', '')"), 'LIKE', "%{$normalizedTerm}%");
            });
        }

        $results = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'status' => true,
            'data' => ['results' => $results],
            'message' => 'Búsqueda de órdenes realizada correctamente',
        ]);
    } catch (\Throwable $th) {
        return response()->json([
            'status' => false,
            'message' => 'Error en búsqueda de órdenes',
            'error' => config('app.debug') ? $th->getMessage() : null,
        ], 500);
    }
}
}