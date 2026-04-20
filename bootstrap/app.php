<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        // 1. Redirect users yang sudah login (ketika mencoba akses /login atau /register)
        $middleware->redirectUsersTo('/dashboard');

        // 2. Custom Redirect untuk guest (belum login) berdasarkan route yang diakses
        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->routeIs('bac-lab.vulnerable.*')) {
                return route('bac-lab.vulnerable.login');
            }
            if ($request->routeIs('bac-lab.secure.*')) {
                return route('bac-lab.secure.login');
            }
            if ($request->routeIs('authorization-lab.*')) {
                return route('authorization-lab.login');
            }

            // Default redirect jika tidak masuk kategori lab di atas
            return route('login');
        });

        // 3. Menambahkan Security Headers ke grup middleware web
        $middleware->web(append: [
            \App\Http\Middleware\SecurityHeaders::class,
        ]);

        // 4. Mendaftarkan Alias Middleware
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();