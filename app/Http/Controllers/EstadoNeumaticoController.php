<?php

namespace App\Http\Controllers;

use App\Models\EstadoNeumatico;
use App\Models\Tarea;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

// TODO: Borrar las respuestas de error para que no se exponga información sensible en producción.
// Se considerará agregar visualización detallada de estados de neumático si aplica.
// Se tiene que agregar la lógica para permitir el uso de imágenes

class EstadoNeumaticoController extends Controller {
    use AuthorizesRequests;
    /**
     * Listado de todos los estados de neumático paginados
     */
    public function index(): JsonResponse {
        try {
            $estado_de_neumaticos = EstadoNeumatico::with('tarea')->paginate(10);

            return response()->json([
                'status'  => true,
                'data'    => $estado_de_neumaticos,
                'message' => 'Estados de neumático obtenidos correctamente',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status'  => false,
                'message' => 'Error al obtener los estados de neumático',
                'error'   => $th->getMessage(),
            ], 400);
        }
    }

    /**
     * Creación de nuevas instancias de estado de neumático en la base de datos
     */
    public function store(Request $request): JsonResponse {
        $validador = $request->validate([
            'tarea_id'               => 'required|integer|exists:tareas,id',
            'delanteros_derechos'    => 'required|boolean',
            'delanteros_izquierdos'  => 'required|boolean',
            'traseros_derechos'      => 'required|boolean',
            'traseros_izquierdos'    => 'required|boolean',
        ]);

        try {
            $tarea = Tarea::findOrFail($validador['tarea_id']);
            $this->authorize('checar-id-mecanico', $tarea);

            $nuevo_estado = EstadoNeumatico::create([
                'tarea_id'               => $validador['tarea_id'],
                'delanteros_derechos'    => $validador['delanteros_derechos'],
                'delanteros_izquierdos'  => $validador['delanteros_izquierdos'],
                'traseros_derechos'      => $validador['traseros_derechos'],
                'traseros_izquierdos'    => $validador['traseros_izquierdos'],
            ]);

            return response()->json([
                'status'  => true,
                'data'    => $nuevo_estado,
                'message' => 'Estado de neumático creado correctamente',
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status'  => false,
                'message' => 'Error al crear un nuevo estado de neumático',
                'error'   => $th->getMessage(),
            ], 400);
        }
    }

    /**
     * Actualización de instancias de estado de neumático en la base de datos
     */
    public function update(Request $request, string $id): JsonResponse {
        $validador = $request->validate([
            'tarea_id'               => 'sometimes|integer|exists:tareas,id',
            'delanteros_derechos'    => 'sometimes|boolean',
            'delanteros_izquierdos'  => 'sometimes|boolean',
            'traseros_derechos'      => 'sometimes|boolean',
            'traseros_izquierdos'    => 'sometimes|boolean',
        ]);

        try {
            $estado = EstadoNeumatico::findOrFail($id);
            $tarea = $estado->tarea;

            if (!Gate::allows('checar-id-mecanico', $tarea)){
                return response()->json(['error' => 'Accion no autorizada'], 403);
            }
            $estado->update($validador);

            return response()->json([
                'status'  => true,
                'data'    => $estado,
                'message' => 'Estado de neumático actualizado correctamente',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status'  => false,
                'message' => 'Error al actualizar el estado de neumático',
                'error'   => $th->getMessage(),
            ], 400);
        }
    }

    /**
     * Borrar una instancia de estado de neumático en la base de datos
     */
    public function destroy(string $id): JsonResponse {
        try {
            $estado = EstadoNeumatico::findOrFail($id);
            $tarea = $estado->tarea;

            if (!Gate::allows('checar-id-mecanico', $tarea)){
                return response()->json(['error' => 'Accion no autorizada'], 403);
            }

            $estado->delete();

            return response()->json(null, 204);
        } catch (\Throwable $th) {
            return response()->json([
                'status'  => false,
                'message' => 'Error al eliminar el estado de neumático',
                'error'   => $th->getMessage(),
            ], 400);
        }
    }
}
