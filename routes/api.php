<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\OrdenController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProductoUsadoController;
use App\Http\Controllers\TareaController;
use App\Http\Controllers\VehiculoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('categorias', CategoriaController::class);
Route::apiResource('productos', ProductoController::class);
Route::apiResource('ordenes', OrdenController::class);
Route::apiResource('clientes', ClienteController::class);
Route::apiResource('vehiculos', VehiculoController::class);
Route::apiResource('tareas', TareaController::class);
Route::apiResource('productos-usados', ProductoUsadoController::class);
