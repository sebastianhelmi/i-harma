<?php

use App\Http\Middleware\CheckRole;
use App\Http\Middleware\DivisionMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->group(base_path('routes/project-manager.php'));
            Route::middleware('web')
                ->group(base_path('routes/head-of-division.php'));
            Route::middleware('web')
                ->group(base_path('routes/purchasing.php'));
            Route::middleware('web')
                ->group(base_path('routes/inventory.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => CheckRole::class,
            'division' => DivisionMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
