<?php

namespace App\Http\Controllers;

use App\Models\ProductoUsado;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

// TODO: Borrar las respuestas de error para que no se exponga información sensible en producción.


class ProductoUsadoController extends Controller {
    /**
     * Listado de productos usados por tarea
     */
    public function index(): JsonResponse {
        try {
            $productos_usados = ProductoUsado::with(['tarea', 'producto'])->paginate(10);
            return response()->json([
                'status' => true,
                'data' => $productos_usados,
                'message' => 'Productos usados obtenidos correctamente',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error al obtener los productos usados',
                'error' => $th->getMessage(),
            ], 400);
        }
    }

    /**
     * Creación de nuevas instancias de productos usados en la base de datos
     */
    public function store(Request $request): JsonResponse{
        $validador = $request->validate([
            'tarea_id' => 'required|integer|exists:tareas,id',
            'producto_id' => 'required|integer|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
        ]);

        try {
            $nuevo_producto_usado = ProductoUsado::create([
                'tarea_id' => $validador['tarea_id'],
                'producto_id' => $validador['producto_id'],
                'cantidad' => $validador['cantidad'],
            ]);

            return response()->json([
                'status' => true,
                'data' => $nuevo_producto_usado,
                'message' => 'Producto usado creado correctamente',
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error al crear un nuevo producto usado',
                'error' => $th->getMessage(),
            ], 400);
        }
    }

    /**
     * Actualizacion de productos usados
     */
    public function update(Request $request, string $id): JsonResponse {
        $validador = $request->validate([
            'tarea_id' => 'sometimes|integer|exists:tareas,id',
            'producto_id' => 'sometimes|integer|exists:productos,id',
            'cantidad' => 'sometimes|integer|min:1',
        ]);

        try {
            $producto_usado = ProductoUsado::with('tarea')->findOrFail($id);
            $producto_usado->update($validador);

            return response()->json([
                'status' => true,
                'data' => $producto_usado,
                'message' => 'Producto usado actualizado correctamente',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error al actualizar el producto usado',
                'error' => $th->getMessage(),
            ], 400);
        }
    }

    /**
     * Eliminación de productos usados
     */
    public function destroy(string $id): JsonResponse{
        try {
            $producto_usado = ProductoUsado::with('tarea')->findOrFail($id);
            $producto_usado->delete();

            return response()->json(null, 204);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error al eliminar el producto usado',
                'error' => $th->getMessage(),
            ], 400);
        }
    }
}
