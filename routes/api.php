<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BuscadorController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EstadoNeumaticoController;
use App\Http\Controllers\FrenosController;
use App\Http\Controllers\MecanicoController;
use App\Http\Controllers\OrdenController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProductoUsadoController;
use App\Http\Controllers\TareaController;
use App\Http\Controllers\TrenDelanteroController;
use App\Http\Controllers\TrenTraseroController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehiculoController;
use App\Http\Middleware\AutorizacionJefe;
use App\Http\Middleware\ChecarRol;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/usuario', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware("throttle:auth")->group(function () {
    //? Registro y login
    Route::post("registro", [AuthController::class, "register"]);
    Route::post("iniciar-sesion", [AuthController::class, "login"]);

    //? Logout (requiere autenticación)
    Route::middleware("auth:sanctum")->group(function () {
        Route::post("cerrar-sesion", [AuthController::class, "logout"]);
    });
});

Route::middleware(['auth:sanctum', 'throttle:limitador_global'])->group(function () {
    //* Usuarios
    Route::get("usuarios", [UserController::class, 'index']);
    Route::put("usuarios/{usuario}", [UserController::class, 'update'])->middleware([AutorizacionJefe::class]);
    Route::delete("usuarios/{usuario}", [UserController::class, 'destroy'])->middleware([AutorizacionJefe::class]);

    //* Mecanicos
    Route::get('mecanicos', [MecanicoController::class, 'getAllMecanicos']);

    //* Categorias
    Route::get('categorias', [CategoriaController::class, 'index']);
    Route::post('categorias', [CategoriaController::class, 'store'])->middleware([AutorizacionJefe::class]);
    // Route::get('categorias/{categoria}', [CategoriaController::class, 'show']);
    Route::put('categorias/{categoria}', [CategoriaController::class, 'update'])->middleware([AutorizacionJefe::class]);
    Route::delete('categorias/{categoria}', [CategoriaController::class, 'destroy'])->middleware([AutorizacionJefe::class]);

    //* Productos
    Route::get('productos', [ProductoController::class, 'index']);
    Route::post('productos', [ProductoController::class, 'store'])->middleware([AutorizacionJefe::class]);
    // Route::get('productos/{producto}', [ProductoController::class, 'show']);
    Route::put('productos/{producto}', [ProductoController::class, 'update'])->middleware([AutorizacionJefe::class]);
    Route::delete('productos/{producto}', [ProductoController::class, 'destroy'])->middleware([AutorizacionJefe::class]);

    //* Ordenes
    Route::get('ordenes', [OrdenController::class, 'index']);
    Route::post('ordenes', [OrdenController::class, 'store'])->middleware([AutorizacionJefe::class]);
    Route::get('ordenes/{orden}', [OrdenController::class, 'show']);
    Route::put('ordenes/{orden}', [OrdenController::class, 'update'])->middleware([AutorizacionJefe::class]);
    Route::delete('ordenes/{orden}', [OrdenController::class, 'destroy'])->middleware([AutorizacionJefe::class]);

    //* Clientes
    Route::get('clientes', [ClienteController::class, 'index']);
    Route::post('clientes', [ClienteController::class, 'store'])->middleware([AutorizacionJefe::class]);
    Route::get('clientes/{cliente}', [ClienteController::class, 'show']);
    Route::put('clientes/{cliente}', [ClienteController::class, 'update'])->middleware([AutorizacionJefe::class]);
    Route::delete('clientes/{cliente}', [ClienteController::class, 'destroy'])->middleware([AutorizacionJefe::class]);

    //* Vehiculos
    Route::get('vehiculos', [VehiculoController::class, 'index']);
    Route::post('vehiculos', [VehiculoController::class, 'store'])->middleware([AutorizacionJefe::class]);
    Route::get('vehiculos/{vehiculo}', [VehiculoController::class, 'show']);
    Route::put('vehiculos/{vehiculo}', [VehiculoController::class, 'update'])->middleware([AutorizacionJefe::class]);
    Route::delete('vehiculos/{vehiculo}', [VehiculoController::class, 'destroy'])->middleware([AutorizacionJefe::class]);

    //* Tareas
    Route::get('tareas', [TareaController::class, 'index']);
    Route::get('tareas/mecanico', [TareaController::class, 'getByMecanico'])->middleware([ChecarRol::class . ':' . User::ROL_JEFE . ',' . User::ROL_MECANICO ]);
    Route::post('tareas', [TareaController::class, 'store'])->middleware([AutorizacionJefe::class]);
    Route::get('tareas/{tarea}', [TareaController::class, 'show']);
    Route::put('tareas/{tarea}', [TareaController::class, 'update'])->middleware([ChecarRol::class . ':' . User::ROL_JEFE . ',' . User::ROL_MECANICO ]); // Gate colocado
    Route::delete('tareas/{tarea}', [TareaController::class, 'destroy'])->middleware([AutorizacionJefe::class]);

    //* Productos Usados
    Route::get('productos-usados', [ProductoUsadoController::class, 'index']);
    Route::post('productos-usados', [ProductoUsadoController::class, 'store'])->middleware([ChecarRol::class . ':' . User::ROL_JEFE . ',' . User::ROL_MECANICO ]); // Gate colocado
    // Route::get('productos-usados/{productoUsado}', [ProductoUsadoController::class, 'show']);
    Route::put('productos-usados/{productoUsado}', [ProductoUsadoController::class, 'update'])->middleware([ChecarRol::class . ':' . User::ROL_JEFE . ',' . User::ROL_MECANICO ]); // Gate colocado
    Route::delete('productos-usados/{productoUsado}', [ProductoUsadoController::class, 'destroy'])->middleware([ChecarRol::class . ':' . User::ROL_JEFE . ',' . User::ROL_MECANICO ]); // Gate colocado

    //* Trenes Delanteros Route::get('trenes-delanteros', [TrenDelanteroController::class, 'index']);
    Route::post('trenes-delanteros', [TrenDelanteroController::class, 'store'])->middleware([ChecarRol::class . ':' . User::ROL_JEFE . ',' . User::ROL_MECANICO ]);
    // Route::get('trenes-delanteros/{trenDelantero}', [TrenDelanteroController::class, 'show']);
    Route::put('trenes-delanteros/{trenDelantero}', [TrenDelanteroController::class, 'update'])->middleware([ChecarRol::class . ':' . User::ROL_JEFE . ',' . User::ROL_MECANICO ]);
    // Route::delete('trenes-delanteros/{trenDelantero}', [TrenDelanteroController::class, 'destroy'])->middleware([ChecarRol::class . ':' . User::ROL_JEFE . ',' . User::ROL_MECANICO ]);

    //* Trenes Traseros
    Route::get('trenes-traseros', [TrenTraseroController::class, 'index']);
    Route::post('trenes-traseros', [TrenTraseroController::class, 'store'])->middleware([ChecarRol::class . ':' . User::ROL_JEFE . ',' . User::ROL_MECANICO ]);
    // Route::get('trenes-traseros/{trenTrasero}', [TrenTraseroController::class, 'show']);
    Route::put('trenes-traseros/{trenTrasero}', [TrenTraseroController::class, 'update'])->middleware([ChecarRol::class . ':' . User::ROL_JEFE . ',' . User::ROL_MECANICO ]);
    // Route::delete('trenes-traseros/{trenTrasero}', [TrenTraseroController::class, 'destroy'])->middleware([ChecarRol::class . ':' . User::ROL_JEFE . ',' . User::ROL_MECANICO ]);

    //* Frenos
    Route::get('frenos', [FrenosController::class, 'index']);
    Route::post('frenos', [FrenosController::class, 'store'])->middleware([ChecarRol::class . ':' . User::ROL_JEFE . ',' . User::ROL_MECANICO ]);
    // Route::get('frenos/{freno}', [FrenosController::class, 'show']);
    Route::put('frenos/{freno}', [FrenosController::class, 'update'])->middleware([ChecarRol::class . ':' . User::ROL_JEFE . ',' . User::ROL_MECANICO ]);
    // Route::delete('frenos/{freno}', [FrenosController::class, 'destroy'])->middleware([ChecarRol::class . ':' . User::ROL_JEFE . ',' . User::ROL_MECANICO ]);

    //* Estados Neumaticos
    Route::get('estados-neumaticos', [EstadoNeumaticoController::class, 'index']);
    Route::post('estados-neumaticos', [EstadoNeumaticoController::class, 'store'])->middleware([ChecarRol::class . ':' . User::ROL_JEFE . ',' . User::ROL_MECANICO ]);
    // Route::get('estados-neumaticos/{estadoNeumatico}', [EstadoNeumaticoController::class, 'show']);
    Route::put('estados-neumaticos/{estadoNeumatico}', [EstadoNeumaticoController::class, 'update'])->middleware([ChecarRol::class . ':' . User::ROL_JEFE . ',' . User::ROL_MECANICO ]);
    // Route::delete('estados-neumaticos/{estadoNeumatico}', [EstadoNeumaticoController::class, 'destroy'])->middleware([ChecarRol::class . ':' . User::ROL_JEFE . ',' . User::ROL_MECANICO ]);
});

//* Buscador para cliente
Route::middleware('throttle:limitador_searching_cliente')->group(function(){
    Route::get('/search/{collection}/{term}', [BuscadorController::class, 'search']);
});
