<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

// TODO: Borrar las respuestas de error para que no se exponga información sensible en producción. Se considerará agregar visualización detallada de productos si aplica. Se tiene que agregar la lógica para permitir el uso de imágenes

class ProductoController extends Controller {
    /**
     * Listado de todos los productos paginados
     */
    public function index(): JsonResponse {
        try {
            $productos = Producto::with('categoria')->paginate(10);
            
            return response()->json([
                'status' => true,
                'data' => $productos,
                'message' => 'Productos obtenidos correctamente',
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
     * Creación de nuevas instancias de productos en la base de datos
     */
    public function store(Request $request): JsonResponse {
        $validador = $request->validate([
            'categoria_id' => 'required|integer|exists:categorias,id',
            'nombre' => 'required|string|max:255',
            'detalles' => 'nullable|string',
            'marca' => 'required|string|max:255',
            'imagen' => 'nullable|string|max:255',
            'stock' => 'required|integer|min:0',
            'precio' => 'required|numeric|min:0',
            'disponibilidad' => 'required|boolean',
        ]);
        try {
            $nuevo_producto = Producto::create([
                'categoria_id' => $validador['categoria_id'],
                'nombre' => $validador['nombre'],
                'detalles' => $validador['detalles'],
                'marca' => $validador['marca'],
                'imagen' => $validador['imagen'],
                'stock' => $validador['stock'],
                'precio' => $validador['precio'],
                'disponibilidad' => $validador['disponibilidad'],
            ]);

            return response()->json([
                'status' => true,
                'data' => $nuevo_producto,
                'message' => 'Producto creado correctamente',
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error al crear un nuevo producto',
                'error' => $th->getMessage(),
            ], 400);
        }
    }


    /**
     * Actualización de instancias de productos en la base de datos
     */
    public function update(Request $request, string $id): JsonResponse {
        $validador = $request->validate([
            'categoria_id' => 'sometimes|integer|exists:categorias,id',
            'nombre' => 'sometimes|string|max:255',
            'detalles' => 'sometimes|string',
            'marca' => 'sometimes|string|max:255',
            'imagen' => 'sometimes|string|max:255',
            'stock' => 'sometimes|integer|min:0',
            'precio' => 'sometimes|numeric|min:0',
            'disponibilidad' => 'sometimes|boolean',
        ]);

        try {
            $producto = Producto::findOrFail($id);
            $producto->update($validador);

            return response()->json([
                'status' => true,
                'data' => $producto,
                'message' => 'Producto actualizado correctamente',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error al actualizar el producto',
                'error' => $th->getMessage(),
            ], 400);
        }
    }

    /**
     * Borrar una instancia de productos en la base de datos
     */
    public function destroy(string $id): JsonResponse {
        try {
            $producto = Producto::findOrFail($id);
            $producto->delete();

            return response()->json(null, 204);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error al eliminar el producto',
                'error' => $th->getMessage(),
            ], 400);
        }
    }
}
