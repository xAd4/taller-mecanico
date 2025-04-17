<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use App\Models\TrenTrasero;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

// TODO: Borrar las respuestas de error para que no se exponga información sensible en producción.
// Se considerará agregar visualización detallada de productos si aplica.
// Se tiene que agregar la lógica para permitir el uso de imágenes

class TrenTraseroController extends Controller {

    use AuthorizesRequests;
    /**
     * Listado de todos los trenes traseros paginados
     */
    public function index(): JsonResponse {
        try {
            $trenes_traseros = TrenTrasero::with('tarea')->paginate(10);

            return response()->json([
                'status'  => true,
                'data'    =>    $trenes_traseros,
                'message' => 'Trenes traseros obtenidos correctamente',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status'  => false,
                'message' => 'Error al obtener los trenes traseros',
                'error'   => $th->getMessage(),
            ], 400);
        }
    }

    /**
     * Creación de nuevas instancias de tren trasero en la base de datos
     */
    public function store(Request $request): JsonResponse {
        $validador = $request->validate([
            'tarea_id'        => 'required|integer|exists:tareas,id',
            'conv'            => 'required|boolean',
            'comba'           => 'required|boolean',
            'brazos_susp'     => 'required|boolean',
            'articulaciones'  => 'required|boolean',
            'conv2'           => 'required|boolean',
            'comba2'          => 'required|boolean',
            'amort'           => 'required|boolean',
        ]);

        try {
            $tarea = Tarea::findOrFail($validador['tarea_id']);
            $this->authorize('checar-id-mecanico', $tarea);

            if ($tarea->trenTrasero()->exists()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Esta tarea ya tiene un tren trasero registrado'
                ], 400);
            }

            $nuevo_tren_trasero = TrenTrasero::create([
                'tarea_id'       => $validador['tarea_id'],
                'conv'           => $validador['conv'],
                'comba'          => $validador['comba'],
                'brazos_susp'    => $validador['brazos_susp'],
                'articulaciones' => $validador['articulaciones'],
                'conv2'           => $validador['conv2'],
                'comba2'          => $validador['comba2'],
                'amort'          => $validador['amort'],
            ]);

            return response()->json([
                'status'  => true,
                'data'    => $nuevo_tren_trasero,
                'message' => 'Tren trasero creado correctamente',
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status'  => false,
                'message' => 'Error al crear un nuevo tren trasero',
                'error'   => $th->getMessage(),
            ], 400);
        }
    }

    /**
     * Actualización de instancias de tren trasero en la base de datos
     */
    public function update(Request $request, string $id): JsonResponse {
        $validador = $request->validate([
            'tarea_id'        => 'sometimes|integer|exists:tareas,id',
            'conv'            => 'sometimes|boolean',
            'comba'           => 'sometimes|boolean',
            'brazos_susp'     => 'sometimes|boolean',
            'articulaciones'  => 'sometimes|boolean',
            'conv2'           => 'sometimes|boolean',
            'comba2'          => 'sometimes|boolean',
            'amort'           => 'sometimes|boolean',
        ]);

        try {
            $tren_trasero = TrenTrasero::findOrFail($id);
            $tarea = $tren_trasero->tarea;

            if (!Gate::allows('checar-id-mecanico', $tarea)){
                return response()->json(['error' => 'Accion no autorizada'], 403);
            }
            $tren_trasero->update($validador);

            return response()->json([
                'status'  => true,
                'data'    => $tren_trasero,
                'message' => 'Tren trasero actualizado correctamente',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status'  => false,
                'message' => 'Error al actualizar el tren trasero',
                'error'   => $th->getMessage(),
            ], 400);
        }
    }

    /**
     * Borrar una instancia de tren trasero en la base de datos
     */
    public function destroy(string $id): JsonResponse {
        try {
            $tren_trasero = TrenTrasero::findOrFail($id);
            $tarea = $tren_trasero->tarea;

            if (!Gate::allows('checar-id-mecanico', $tarea)){
                return response()->json(['error' => 'Accion no autorizada'], 403);
            }

            $tren_trasero->delete();

            return response()->json(null, 204);
        } catch (\Throwable $th) {
            return response()->json([
                'status'  => false,
                'message' => 'Error al eliminar el tren trasero',
                'error'   => $th->getMessage(),
            ], 400);
        }
    }
}
