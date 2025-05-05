<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MecanicoController extends Controller {
    public function getAllMecanicos(): JsonResponse {
        try {
            $mecanicos = User::with("tareasAsignadas")->orderBy('created_at', 'desc')
                ->where('rol', User::ROL_MECANICO)
                ->get();
    
            return response()->json([
                'status' => true,
                'data' => $mecanicos,
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
}
