<?php

namespace App\Providers;

use App\Models\Tarea;
use App\Models\User;
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

        // // Gate para permitir actualizar una tarea si el id del usuario coincide con el id de la tarea
        // Gate::define('actualizar-tarea', function (User $user, Tarea $tarea){
        //     return $user->id === $tarea->mecanico_id;
        // });

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
