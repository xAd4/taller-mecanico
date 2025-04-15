<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AutorizacionMecanico
{
    /**
     * Manejo de autorización para mecánicos.
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response {
        if ($request->user()->rol !== User::ROL_MECANICO){
            return response()->json(["error" => "Solo los mecánicos pueden acceder a esta función"], 403);
        }
        return $next($request);
    }
}
