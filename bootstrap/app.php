<?php

use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\CaptureHttpRequest;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            HandleInertiaRequests::class,
            CaptureHttpRequest::class,
        ]);
         $middleware->validateCsrfTokens(except: [
            '/vote',
            '/handle-vote-request',
         ]);
         $middleware->trustProxies(at: "*");
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
