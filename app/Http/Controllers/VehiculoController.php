<?php

namespace App\Http\Controllers;

use App\Models\Vehiculo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

// TODO: Borrar las respuestas de error para que no se exponga información sensible en producción.

class VehiculoController extends Controller {
    /**
     * Listado de vehiculos de clientes
     */
    public function index(): JsonResponse {
        try {
            $vehiculos = Vehiculo::with('cliente')->paginate(10);
            return response()->json([
                'status' => true,
                'data' => $vehiculos,
                'message' => 'Vehiculos obtenidos correctamente',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error al obtener los vehiculos',
                'error' => $th->getMessage(),
            ], 400);
        }
    }

    /**
     * Creación de instancias de vehiculos de cliente en la base de datos
     */
    public function store(Request $request): JsonResponse {
        $validador = $request->validate([
            'cliente_id' => 'required|integer',
            'modelo' => 'required|string|max:255',
            'marca' => 'required|string|max:255',
            'color' => 'required|string|max:255',
            'matricula' => 'required|string|max:255',
            'kilometraje' => 'required|integer|min:0',
            'numero_de_serie' => 'nullable|string|max:255',
            'numero_de_motor' => 'nullable|string|max:255',
            'fecha_de_compra' => 'nullable|date',
        ]);

        try {
            $nuevo_vehiculo = Vehiculo::create([
                'cliente_id' => $validador['cliente_id'],
                'modelo' => $validador['modelo'],
                'marca' => $validador['marca'],
                'color' => $validador['color'],
                'matricula' => $validador['matricula'],
                'kilometraje' => $validador['kilometraje'],
                'numero_de_serie' => $validador['numero_de_serie'],
                'numero_de_motor' => $validador['numero_de_motor'],
                'fecha_de_compra' => $validador['fecha_de_compra'],
            ]);

            return response()->json([
                'status' => true,
                'data' => $nuevo_vehiculo,
                'message' => 'Vehiculo creado correctamente',
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error al crear un nuevo vehiculo',
                'error' => $th->getMessage(),
            ], 400);
        }
    }

    /**
     * Especifiación de un vehiculo
     */
    public function show(string $id): JsonResponse {
        try {
            $vehiculo = Vehiculo::with('cliente')->findOrFail($id);
            return response()->json([
                'status' => true,
                'data' => $vehiculo,
                'message' => 'Vehiculo obtenido correctamente',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error al obtener el vehiculo',
                'error' => $th->getMessage(),
            ], 400);
        }
    }

    /**
     * Actualización de instancias de vehiculos en la base de datos
     */
    public function update(Request $request, string $id): JsonResponse{
        $validador = $request->validate([
            'cliente_id' => 'sometimes|integer',
            'modelo' => 'sometimes|string|max:255',
            'marca' => 'sometimes|string|max:255',
            'color' => 'sometimes|string|max:255',
            'matricula' => 'sometimes|string|max:255',
            'kilometraje' => 'sometimes|integer|min:0',
            'numero_de_serie' => 'sometimes|string|max:255',
            'numero_de_motor' => 'sometimes|string|max:255',
            'fecha_de_compra' => 'sometimes|date',
        ]);

        try {
            $vehiculo = Vehiculo::findOrFail($id);
            $vehiculo->update($validador);

            return response()->json([
                'status' => true,
                'data' => $vehiculo,
                'message' => 'Vehiculo actualizado correctamente',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error al actualizar el vehiculo',
                'error' => $th->getMessage(),
            ], 400);
        }
    }

    /**
     * Eliminación de instancias de vehiculos en la base de datos
     */
    public function destroy(string $id): JsonResponse{
        try {
            $vehiculo = Vehiculo::findOrFail($id);
            $vehiculo->delete();

            return response()->json(null, 204);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error al eliminar el vehiculo',
                'error' => $th->getMessage(),
            ], 400);
        }
    }
}
