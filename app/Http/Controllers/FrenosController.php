<?php

namespace App\Http\Controllers;

use App\Models\Frenos;
use App\Models\Tarea;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

// TODO: Borrar las respuestas de error para que no se exponga información sensible en producción. Se considerará agregar visualización detallada de productos si aplica. Se tiene que agregar la lógica para permitir el uso de imágenes

class FrenosController extends Controller {
    use AuthorizesRequests;
    /**
     * Listado de todos los frenos paginados
     */
    public function index(): JsonResponse {
        try {
            $frenos = Frenos::with('tarea')->paginate(10);

            return response()->json([
                'status'  => true,
                'data'    => $frenos,
                'message' => 'Frenos obtenidos correctamente',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status'  => false,
                'message' => 'Error al obtener los frenos',
                'error'   => $th->getMessage(),
            ], 400);
        }
    }

    /**
     * Creación de nuevas instancias de frenos en la base de datos
     */
    public function store(Request $request): JsonResponse {
        $validador = $request->validate([
            'tarea_id'         => 'required|integer|exists:tareas,id',
            'delanteros'       => 'required|boolean',
            'traseros'         => 'required|boolean',
            'estacionamiento'  => 'required|boolean',
            'numero_cricket'   => 'required|boolean',
        ]);

        try {
            $tarea = Tarea::findOrFail($validador['tarea_id']);
            $this->authorize('checar-id-mecanico', $tarea);

            $nuevo_freno = Frenos::create([
                'tarea_id'        => $validador['tarea_id'],
                'delanteros'      => $validador['delanteros'],
                'traseros'        => $validador['traseros'],
                'estacionamiento' => $validador['estacionamiento'],
                'numero_cricket'  => $validador['numero_cricket'],
            ]);

            return response()->json([
                'status'  => true,
                'data'    => $nuevo_freno,
                'message' => 'Freno creado correctamente',
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status'  => false,
                'message' => 'Error al crear un nuevo freno',
                'error'   => $th->getMessage(),
            ], 400);
        }
    }

    /**
     * Actualización de instancias de frenos en la base de datos
     */
    public function update(Request $request, string $id): JsonResponse {
        $validador = $request->validate([
            'tarea_id'         => 'sometimes|integer|exists:tareas,id',
            'delanteros'       => 'sometimes|boolean',
            'traseros'         => 'sometimes|boolean',
            'estacionamiento'  => 'sometimes|boolean',
            'numero_cricket'   => 'sometimes|boolean',
        ]);

        try {
            $freno = Frenos::findOrFail($id);
            $tarea = $freno->tarea;

            if (!Gate::allows('checar-id-mecanico', $tarea)){
                return response()->json(['error' => 'Accion no autorizada'], 403);
            }

            $freno->update($validador);

            return response()->json([
                'status'  => true,
                'data'    => $freno,
                'message' => 'Freno actualizado correctamente',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status'  => false,
                'message' => 'Error al actualizar el freno',
                'error'   => $th->getMessage(),
            ], 400);
        }
    }

    /**
     * Borrar una instancia de frenos en la base de datos
     */
    public function destroy(string $id): JsonResponse {
        try {
            $freno = Frenos::findOrFail($id);
            $tarea = $freno->tarea;

            if (!Gate::allows('checar-id-mecanico', $tarea)){
                return response()->json(['error' => 'Accion no autorizada'], 403);
            }

            $freno->delete();

            return response()->json(null, 204);
        } catch (\Throwable $th) {
            return response()->json([
                'status'  => false,
                'message' => 'Error al eliminar el freno',
                'error'   => $th->getMessage(),
            ], 400);
        }
    }
}
