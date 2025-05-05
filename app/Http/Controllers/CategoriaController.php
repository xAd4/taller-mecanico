<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

// TODO: Borrar las respuestas de error para que no se exponga información sensible en producción. Se considerará agregar paginación en las categorías si aplica

class CategoriaController extends Controller {
    /**
     * Listado de todas las categorias
     */
    public function index(): JsonResponse {
        try {
            $categorias = Categoria::with('productos')->orderBy('created_at', 'desc')->get();
            return response()->json([
                'status' => true,
                'data' => $categorias,
                'message' => 'Categorias obtenidas correctamente',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error al obtener las categorias',
                'error' => $th->getMessage(),
            ], 400);
        }
    }

    /**
     * Creación de nuevas instancias de categorías en la base de datos
     */
    public function store(Request $request): JsonResponse {
        $validador = $request->validate([
            'disponibilidad' => 'nullable|boolean',
            'nombre' => 'required|string|max:255',
        ]);

        try {
            $nueva_categoria = Categoria::create([
                'nombre' => $validador['nombre'],
            ]);

            return response()->json([
                'status' => true,
                'data' => $nueva_categoria,
                'message' => 'Categoria creada correctamente',
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error al crear una nueva categoria',
                'error' => $th->getMessage(),
            ], 400);
        }
    }

    /**
     * Actualización de instancias de categorías en la base de datos
     */
    public function update(Request $request, string $id): JsonResponse {
        $validador = $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'disponibilidad' => 'sometimes|boolean',
        ]);

        try {
            $categoria = Categoria::findOrFail($id);
            $categoria->update($validador);

            return response()->json([
                'status' => true,
                'data' => $categoria,
                'message' => 'Categoria actualizada correctamente',
            ], 200);


        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error al actualizar la categoria',
                'error' => $th->getMessage(),
            ], 400);
        }
    }

    /**
     * Borrar una instancia de categoría de la base de datos.
     */
    public function destroy(string $id): JsonResponse {
        try {
            $categoria = Categoria::findOrFail($id);
            $categoria->delete();
            return response()->json(null, 204);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error al borrar la categoria',
                'error' => $th->getMessage(),
            ], 400);
        }
    }
}
