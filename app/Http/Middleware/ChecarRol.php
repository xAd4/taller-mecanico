<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ChecarRol {
    /**
     * Manejar si un usuario posee algún rol.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response {
        if (!in_array($request->user()->rol, $roles)) {
            return response()->json(["error" => "Acceso no autorizado"], 403);
        }
        return $next($request);
    }
}