<?php

namespace App\Http\Controllers;

use App\Models\Orden;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

// TODO: Borrar las respuestas de error para que no se exponga información sensible en producción

class OrdenController extends Controller {
    /**
     * Listado de órdenes hechas por los jefes
     */
    public function index(): JsonResponse {
        try {
            $ordenes = Orden::with(['cliente', 'vehiculo'])->paginate(50);

            return response()->json([
                'status' => true,
                'data' => $ordenes,
                'message' => 'Órdenes obtenidas correctamente',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error al obtener los productos',
                'error' => $th->getMessage(),
            ], 400);
        }
    }

    /**
     * Creación de nuevas instancias de órdenes hechas por jefes en la base de datos
     */
    public function store(Request $request): JsonResponse {
        $validador = $request->validate([
            'cliente_id' => 'required|integer|exists:clientes,id',
            'vehiculo_id' => 'required|integer|exists:vehiculos,id',
            'detalle_de_trabajos_a_realizar' => 'nullable|string',
            'recepcion' => 'required|date',
            'prometido' => 'nullable|date',
            'cambio_de_aceite' => 'boolean|nullable',
            'cambio_de_filtro' => 'boolean|nullable',
            'detalles_de_entrada_del_vehiculo' => 'nullable|string|max:255',
        ]);

        try {
            $nueva_orden = Orden::create([
                'cliente_id' => $validador['cliente_id'],
                'vehiculo_id' => $validador['vehiculo_id'],
                'detalle_de_trabajos_a_realizar' => $validador['detalle_de_trabajos_a_realizar'],
                'recepcion' => $validador['recepcion'],
                'prometido' => $validador['prometido'],
                'cambio_de_aceite' => $validador['cambio_de_aceite'],
                'cambio_de_filtro' => $validador['cambio_de_filtro'],
                'detalles_de_entrada_del_vehiculo' => $validador['detalles_de_entrada_del_vehiculo'],
            ]);

            return response()->json([
                'status' => true,
                'data' => $nueva_orden,
                'message' => 'Orden creada correctamente',
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error al crear una nueva orden',
                'error' => $th->getMessage(),
            ], 400);
        }
    }

    /**
     * Visualización específica de una orden
     */
    public function show(string $id): JsonResponse {
        try {
            $orden = Orden::with(['cliente', 'vehiculo', 'tareas.mecanico'])->findOrFail($id);

            return response()->json([
                'status' => true,
                'data' => $orden,
                'message' => 'Orden obtenida correctamente',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error al obtener la orden',
                'error' => $th->getMessage(),
            ], 400);
        }
    }

    /**
     * Actualización de instancias de órdenes hechas por jefes en la base de datos
     */
    public function update(Request $request, string $id): JsonResponse {
        $validador = $request->validate([
            'cliente_id' => 'sometimes|integer|exists:clientes,id',
            'vehiculo_id' => 'sometimes|integer|exists:vehiculos,id',
            'detalle_de_trabajos_a_realizar' => 'sometimes|string|max:255',
            'recepcion' => 'sometimes|date',
            'prometido' => 'sometimes|date',
            'cambio_de_aceite' => 'sometimes|boolean|nullable',
            'cambio_de_filtro' => 'sometimes|boolean|nullable',
            'detalles_de_entrada_del_vehiculo' => 'sometimes|string|max:255',
        ]);

        try {
            $orden = Orden::findOrFail($id);
            $orden->update($validador);

            return response()->json([
                'status' => true,
                'data' => $orden,
                'message' => 'Orden actualizada correctamente',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error al actualizar la orden',
                'error' => $th->getMessage(),
            ], 400);
        }
    }

    /**
     * Eliminación de instancias de órdenes hechas por jefes en la base de datos
     */
    public function destroy(string $id): JsonResponse {
        try {
            $orden = Orden::findOrFail($id);
            $orden->delete();

            return response()->json(null, 204);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error al eliminar la orden',
                'error' => $th->getMessage(),
            ], 400);
        }
    }
}
