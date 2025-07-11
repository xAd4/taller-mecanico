<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller {
    public function index(): JsonResponse {
        try {
            $usuarios = User::orderByRaw('disponible DESC')->with("tareasAsignadas")->orderBy('created_at', 'desc')->get();
    
            return response()->json([
                'status' => true,
                'data' => $usuarios,
                'message' => 'Mecánicos obtenidos correctamente',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error al obtener los mecánicos',
                'error' => $th->getMessage(),
            ], 400);
        }
    }

    public function update(Request $request, string $id): JsonResponse {
        $validador = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'password' => 'sometimes|string|min:8|confirmed',
            'rol' => 'sometimes|in:' . User::ROL_JEFE . ',' . User::ROL_MECANICO,
            'disponible' => 'sometimes|boolean',
        ]);

        try {
            $usuario = User::findOrFail($id);

            $usuario->update([
                'name' => $validador['name'],
                'email' => $validador['email'],
                'password' => Hash::make($validador['password']),
                "rol" => $validador['rol'],
                "disponible" => $validador['disponible'],
            ]);

            return response()->json([
                'status' => true,
                'data' => $usuario,
                'message' => 'Usuario actualizado correctamente',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error al actualizar el usuario',
                'error' => $th->getMessage(),
            ], 400);
        }
    }

    public function destroy(string $id): JsonResponse {
        try {
            $usuario = User::findOrFail($id);
            $usuario->delete();
            return response()->json(null, 204);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error al eliminar el usuario',
                'error' => $th->getMessage(),
            ], 400);
        }
    }
}
