<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AutorizacionJefe {
    /**
     * Manejo de autorización para jefes.
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response {
        if ($request->user()->rol !== User::ROL_JEFE){
            return response()->json(["error" => "Solo los jefes pueden acceder a esta función"], 403);
        }
        return $next($request);
    }
}
