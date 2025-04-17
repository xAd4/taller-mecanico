<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use App\Models\TrenDelantero;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

// TODO: Borrar las respuestas de error para que no se exponga información sensible en producción. Se considerará agregar visualización detallada de productos si aplica. Se tiene que agregar la lógica para permitir el uso de imágenes

class TrenDelanteroController extends Controller {
    use AuthorizesRequests;
    /**
     * Listado de todos los trenes delanteros paginados
     */
    public function index(): JsonResponse {
        try {
            $trenes_delanteros = TrenDelantero::with('tarea')->paginate(10);

            return response()->json([
                'status'  => true,
                'data'    => $trenes_delanteros,
                'message' => 'Trenes delanteros obtenidos correctamente',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status'  => false,
                'message' => 'Error al obtener los trenes delanteros',
                'error'   => $th->getMessage(),
            ], 400);
        }
    }

    /**
     * Creación de nuevas instancias de tren delantero en la base de datos
     */
    public function store(Request $request): JsonResponse {
        $validador = $request->validate([
            'tarea_id'        => 'required|integer|exists:tareas,id',
            'conv'            => 'required|boolean',
            'comba'           => 'required|boolean',
            'avance'          => 'required|boolean',
            'rotulas'         => 'required|boolean',
            'punteros'        => 'required|boolean',
            'bujes'           => 'required|boolean',
            'caja_direccion'  => 'required|boolean',
            'conv2'           => 'required|boolean',
            'comba2'          => 'required|boolean',
            'avance2'         => 'required|boolean',
            'amort'           => 'required|boolean',
        ]);

        try {

            $tarea = Tarea::findOrFail($validador['tarea_id']);
            $this->authorize('checar-id-mecanico', $tarea);

            if ($tarea->trenDelantero()->exists()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Esta tarea ya tiene un tren delantero registrado'
                ], 400);
            }

            $nuevo_tren_delantero = TrenDelantero::create([
                'tarea_id'       => $validador['tarea_id'],
                'conv'           => $validador['conv'],
                'comba'          => $validador['comba'],
                'avance'         => $validador['avance'],
                'rotulas'        => $validador['rotulas'],
                'punteros'       => $validador['punteros'],
                'bujes'          => $validador['bujes'],
                'caja_direccion' => $validador['caja_direccion'],
                'conv2'          => $validador['conv2'],
                'comba2'         => $validador['comba2'],
                'avance2'        => $validador['avance2'],
                'amort'          => $validador['amort'],
            ]);

            return response()->json([
                'status'  => true,
                'data'    => $nuevo_tren_delantero,
                'message' => 'Tren delantero creado correctamente',
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status'  => false,
                'message' => 'Error al crear un nuevo tren delantero',
                'error'   => $th->getMessage(),
            ], 400);
        }
    }

    /**
     * Actualización de instancias de tren delantero en la base de datos
     */
    public function update(Request $request, string $id): JsonResponse {
        $validador = $request->validate([
            'tarea_id'        => 'sometimes|integer|exists:tareas,id',
            'conv'            => 'sometimes|boolean',
            'comba'           => 'sometimes|boolean',
            'avance'          => 'sometimes|boolean',
            'rotulas'         => 'sometimes|boolean',
            'punteros'        => 'sometimes|boolean',
            'bujes'           => 'sometimes|boolean',
            'caja_direccion'  => 'sometimes|boolean',
            'conv2'           => 'sometimes|boolean',
            'comba2'          => 'sometimes|boolean',
            'avance2'         => 'sometimes|boolean',
            'amort'           => 'sometimes|boolean',
        ]);

        try {
            $tren_delantero = TrenDelantero::findOrFail($id);
            $tarea = $tren_delantero->tarea;

            if (!Gate::allows('checar-id-mecanico', $tarea)){
                return response()->json(['error' => 'Accion no autorizada'], 403);
            }

            $tren_delantero->update($validador);

            return response()->json([
                'status'  => true,
                'data'    => $tren_delantero,
                'message' => 'Tren delantero actualizado correctamente',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status'  => false,
                'message' => 'Error al actualizar el tren delantero',
                'error'   => $th->getMessage(),
            ], 400);
        }
    }

    /**
     * Borrar una instancia de tren delantero en la base de datos
     */
    public function destroy(string $id): JsonResponse {
        try {
            $tren_delantero = TrenDelantero::findOrFail($id);
            $tarea = $tren_delantero->tarea;

            if (!Gate::allows('checar-id-mecanico', $tarea)){
                return response()->json(['error' => 'Accion no autorizada'], 403);
            }

            $tren_delantero->delete();

            return response()->json(null, 204);
        } catch (\Throwable $th) {
            return response()->json([
                'status'  => false,
                'message' => 'Error al eliminar el tren delantero',
                'error'   => $th->getMessage(),
            ], 400);
        }
    }
}
