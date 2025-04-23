<?php

namespace App\Providers;

use App\Models\ProductoUsado;
use App\Models\Tarea;
use App\Models\User;
use App\Observers\TareaObserver;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {

    public function register(): void {
        //
    }

    public function boot(): void {

        //* Gates
        // Checar que solo el mecanico pueda manipular su propia tarea y no la de otros mecanicos
        Gate::define('checar-id-mecanico', function (User $user, Tarea $tarea) {
            return $user->id === $tarea->mecanico_id;
        });

        //* Observers
        // Al crear una tarea, se crea automaticamente sus instancias relacionadas
        Tarea::observe(TareaObserver::class);

        //* Limitadores para rutas
        RateLimiter::for('auth', function (Request $request) {
            return Limit::perMinute(5)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('limitador_global', function (Request $request) {
            return Limit::perMinute(20)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('limitador_searching_cliente', function (Request $request) {
            return Limit::perMinute(10)->by($request->user()?->id ?: $request->ip());
        });


    }
}
