<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\ProductoUsado;
use App\Models\Tarea;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

// TODO: Borrar las respuestas de error para que no se exponga información sensible en producción.
class ProductoUsadoController extends Controller {

    use AuthorizesRequests;
    /**
     * Listado de productos usados por tarea
     */
    public function index(): JsonResponse {
        try {
            $productos_usados = ProductoUsado::with(['producto', 'tarea'])->get();
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
            // Valida que hayan stocks suficientes en la base de datos
            $producto = Producto::find($validador['producto_id']); 
            $tarea = Tarea::findOrFail($validador['tarea_id']);
            // $this->authorize('checar-id-mecanico', $tarea);

            // Validacion de stock
            if ($producto->stock < $validador['cantidad']) {
                return response()->json([
                    'status' => false,
                    'message' => 'Stock insuficiente. Disponible: ' . $producto->stock
                ], 400);
            }

            $nuevo_producto_usado = ProductoUsado::create([
                'tarea_id' => $validador['tarea_id'],
                'producto_id' => $validador['producto_id'],
                'cantidad' => $validador['cantidad'],

            ]);

            // Cuando se usa un producto, su stock decrece
            $producto->decrement('stock', $validador['cantidad']);

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
     * Visualización específica de los productos usados de una tarea
     */
    public function show(string $tareaId): JsonResponse {
        try {
            
            $productosUsados = ProductoUsado::where('tarea_id', $tareaId)->with('tarea')->get();
    
            return response()->json([
                'status' => true,
                'data' => $productosUsados,
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
     * Actualizacion de productos usados
     */
    public function update(Request $request, string $id): JsonResponse {
        $validador = $request->validate([
            'tarea_id' => 'sometimes|integer|exists:tareas,id',
            'producto_id' => 'sometimes|integer|exists:productos,id',
            'cantidad' => 'sometimes|integer|min:1',
        ]);

        try {

            // Se calculan los stocks que habia anteriormente, crecera o bajara depende de la cantidad utilizada

            $producto_usado = ProductoUsado::with('tarea')->findOrFail($id);
            $producto = $producto_usado->producto;
            $tarea = $producto_usado->tarea;

            // if (!Gate::allows('checar-id-mecanico', $tarea)){
            //     return response()->json(['error' => 'Accion no autorizada'], 403);
            // }

            $nueva_cantidad = $validador['cantidad'] ?? $producto_usado->cantidad;
            $diferencia = $nueva_cantidad - $producto_usado->cantidad;
    
            // Validar stock (considerando la cantidad actual)
            if ($producto->stock < $diferencia) {
                return response()->json([
                    'status' => false,
                    'message' => 'Stock insuficiente. Disponible: ' . $producto->stock
                ], 400);
            }
            

            // Transacción para asegurar consistencia
            DB::transaction(function () use ($producto_usado, $producto, $nueva_cantidad, $diferencia) {
                // Actualizar stock
                $producto->decrement('stock', $diferencia);

                // Actualizar producto usado
                $producto_usado->update(['cantidad' => $nueva_cantidad]);
            });

            return response()->json([
                'status' => true,
                'data' => $producto_usado->fresh(), // Recargar modelo con total actualizado
                'message' => 'Producto usado actualizado correctamente'
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error al actualizar el producto usado',
                'error' => config('app.debug') ? $th->getMessage() : null
        ], 400);
    }
}

    /**
     * Eliminación de productos usados
     */
    public function destroy(string $id): JsonResponse {
        // Se calcula el stock que tenia antes de borrarse, y cuando se borra una instancia se devuelven todos los stocks utilizados
        try {
            $producto_usado = ProductoUsado::findOrFail($id);
            $tarea = $producto_usado->tarea;
    
            if (!Gate::allows('checar-id-mecanico', $tarea)) {
                return response()->json(['error' => 'Accion no autorizada'], 403);
            }
    
            // Recuperar el producto asociado
            $producto = $producto_usado->producto;
    
            // Transacción para asegurar consistencia
            DB::transaction(function () use ($producto_usado, $producto) {
                // Restaurar el stock del producto
                $producto->increment('stock', $producto_usado->cantidad);
    
                // Eliminar el producto usado
                $producto_usado->delete();
            });
    
            return response()->json(null, 204);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error al eliminar el producto usado',
                'error' => config('app.debug') ? $th->getMessage() : null,
            ], 400);
        }
    }
}
