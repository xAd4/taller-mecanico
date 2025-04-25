<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

// TODO: Borrar las respuestas de error para que no se exponga información sensible en producción.

class TareaController extends Controller {
    /**
     * Listado de tareas llevadas por mecánicos
     */
    public function index(): JsonResponse {
        try {
            $tareas = Tarea::with(['orden','productosUsados','mecanico','trenDelantero','trenTrasero','frenos','estadoNeumaticos'])->paginate(10);
            return response()->json([
                'status' => true,
                'data' => $tareas,
                'message' => 'Tareas obtenidas correctamente',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error al obtener las tareas',
                'error' => $th->getMessage(),
            ], 400);
        }
    }

    public function getByMecanico(Request $request): JsonResponse {
        try {
            $mecanicoId = $request->user()->id;
            
            $tareas = Tarea::with([
                'orden.cliente',
                'orden.vehiculo',
                'productosUsados.producto',
                'trenDelantero',
                'trenTrasero',
                'frenos',
                'estadoNeumaticos'
            ])
            ->where('mecanico_id', $mecanicoId)
            ->orderBy('created_at', 'desc')
            ->get();
 
            return response()->json([
                'status' => true,
                'data' => $tareas,
                'message' => 'Tareas del mecánico obtenidas correctamente'
            ], 200);
 
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error al obtener las tareas',
                'error' => config('app.debug') ? $th->getMessage() : null
            ], 500);
        }
    }

    /**
     * Creación de instancias de tareas hechas por jefes para mecánicos en la base de datos
     */
    public function store(Request $request): JsonResponse {
        $validador = $request->validate([
            'orden_id' => 'required|integer|exists:ordens,id',
            'mecanico_id' => 'required|integer|exists:users,id',
            'estado_de_trabajo' => 'required|in:pendiente,en_proceso,pendiente_de_facturacion,completado',
            'notificacion_al_cliente' => 'nullable|string'
        ]);

        try {
            $nueva_tarea = Tarea::create([
                'orden_id' => $validador['orden_id'],
                'mecanico_id' => $validador['mecanico_id'],
                'estado_de_trabajo' => $validador['estado_de_trabajo'],
                'notificacion_al_cliente' => $validador['notificacion_al_cliente']
            ]);



            return response()->json([
                'status' => true,
                'data' => $nueva_tarea,
                'message' => 'Tarea creada correctamente',
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error al crear una nueva tarea',
                'error' => $th->getMessage(),
            ], 400);
        }
    }

    /**
     * Visualización de tareas llevadas por mecánicos
     */
    public function show(string $id): JsonResponse{
        try {
            $tarea = Tarea::with(['orden.cliente','orden.vehiculo','productosUsados','mecanico','trenDelantero','trenTrasero','frenos','estadoNeumaticos'])->findOrFail($id);
            return response()->json([
                'status' => true,
                'data' => $tarea,
                'message' => 'Tarea obtenida correctamente',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error al obtener la tarea',
                'error' => $th->getMessage(),
            ], 400);
        }
    }

    /**
     * Actualización de instancias de tareas llevadas por mecánicos en la base de datos
     */
    public function update(Request $request, string $id): JsonResponse {
        $validador = $request->validate([

            'estado_de_trabajo' => 'sometimes|in:pendiente,en_proceso,pendiente_de_facturacion,completado',
            'notificacion_al_cliente' => 'sometimes|string'
        ]);

        try {
            $tarea = Tarea::findOrFail($id);

             if (!Gate::allows('checar-id-mecanico', $tarea)){
                 return response()->json(['error' => 'Accion no autorizada'], 403);
             }

            $tarea->update($validador);

            return response()->json([
                'status' => true,
                'data' => $tarea,
                'message' => 'Tarea actualizada correctamente',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error al actualizar la tarea',
                'error' => $th->getMessage(),
            ], 400);
        }
    }

    /**
     * Borrar una instancia de tareas hechas por jefes llevadas por mecánicos
     */
    public function destroy(string $id): JsonResponse {
        try {
            $tarea = Tarea::findOrFail($id);
            $tarea->delete();

            return response()->json(null, 204);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error al eliminar la tarea',
                'error' => $th->getMessage(),
            ], 400);
        }
    }
}
