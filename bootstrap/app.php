<?php

use App\Http\Middleware\isAdminMiddleware;
use App\Http\Middleware\isUserMiddleware;
use App\Http\Middleware\PreventBackHistory;
use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'guest' => RedirectIfAuthenticated::class,
            'isAdmin' => isAdminMiddleware::class,
            'isUser' => isUserMiddleware::class,
            'PreventBackHistory' => PreventBackHistory::class,
        ]);
    })
    ->withExceptions()->create();
