<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

// TODO: Borrar las respuestas de error para que no se exponga información sensible en producción

class ClienteController extends Controller {
    /**
     * Listado de clientes
     */
    public function index(): JsonResponse {
        try {
            $clientes = Cliente::orderByRaw('disponible DESC')->orderBy('created_at','desc')->get();

            return response()->json([
                'status' => true,
                'data' => $clientes,
                'message' => 'Clientes obtenidos correctamente',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error al obtener los clientes',
                'error' => $th->getMessage(),
            ], 400);
        }
    }

    /**
     * Creación de nuevas instancias de clientes en la base de datos
     */
    public function store(Request $request): JsonResponse {
        $validador = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:clientes,email',
            'rut' => 'required|string|max:12',
            'telefono' => 'required|string|max:20',
            'domicilio' => 'nullable|string|max:255',

        ]);

        try {
            $nuevo_cliente = Cliente::create([
                'nombre' => $validador['nombre'],
                'email' => $validador['email'],
                'rut' => $validador['rut'],
                'telefono' => $validador['telefono'],
                'domicilio' => $validador['domicilio'] ?? "N/A",
                'disponible' => true,
            ]);

            return response()->json([
                'status' => true,
                'data' => $nuevo_cliente,
                'message' => 'Cliente creado correctamente',
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error al crear cliente',
                'error' => $th->getMessage(),
            ], 400);
        }
    }

  
    /**
     * Actualización de instancias de clientes en la base de datos
     */
    public function update(Request $request, string $id): JsonResponse {
        $validador = $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255|unique:clientes,email,' . $id,
            'rut' => 'sometimes|string|max:12',
            'telefono' => 'sometimes|string|max:20',
            'domicilio' => 'sometimes|string|max:255',
            'disponible' => 'nullable|boolean',

        ]);

        try {
            $cliente = Cliente::findOrFail($id);
            $cliente->update($validador);

            return response()->json([
                'status' => true,
                'data' => $cliente,
                'message' => 'Cliente actualizado correctamente',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error al actualizar el cliente',
                'error' => $th->getMessage(),
            ], 400);
        }
    }

    /**
     * Eliminación de instancias de clientes en la base de datos
     */
    public function destroy(string $id): JsonResponse {
        try {
            $cliente = Cliente::findOrFail($id);
            $cliente->delete();

            return response()->json(null, 204);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error al eliminar el cliente',
                'error' => $th->getMessage(),
            ], 400);
        }
    }
}
