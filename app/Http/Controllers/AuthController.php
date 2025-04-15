<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {
    /**
     * Registrar un nuevo usuario.
     *
     * @param Request $request La solicitud HTTP entrante que contiene los detalles del usuario.
     * @return JsonResponse Una respuesta JSON con el token de acceso y los detalles del usuario, o un mensaje de error.
     */
    public function register(Request $request): JsonResponse {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
                'password_confirmation' => 'required|string',
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                "rol" => User::ROL_MECANICO,
            ]);

            return response()->json([
                'access_token' => $user->createToken('auth_token')->plainTextToken,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'created_at' => $user->created_at,
                ],
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Registro fallido',
            ], 500);
        }
    }

    /**
     * Iniciar sesión de un usuario existente.
     *
     * @param Request $request La solicitud HTTP entrante que contiene las credenciales de inicio de sesión.
     * @return JsonResponse Una respuesta JSON con el token de acceso y los detalles del usuario, o un mensaje de error.
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string|min:8',
            ]);

            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'message' => 'Credenciales inválidas',
                ], 401);
            }

            $user = User::where('email', $request->email)->firstOrFail();

            return response()->json([
                'access_token' => $user->createToken('auth_token')->plainTextToken,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'created_at' => $user->created_at,
                ],
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Inicio de sesión fallido',
            ], 500);
        }
    }

    /**
     * Cerrar sesión del usuario autenticado.
     *
     * @param Request $request La solicitud HTTP entrante del usuario autenticado.
     * @return JsonResponse Una respuesta JSON que indica éxito o fallo.
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $request->user()->tokens()->delete();
            return response()->json(null, 204);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Cierre de sesión fallido',
            ], 500);
        }
    }
}