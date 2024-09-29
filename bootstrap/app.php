<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'ensure.otp.is.verified' => \App\Http\Middleware\EnsureOtpIsVerified::class,
        ]);
    })->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'module.acl' => \App\Http\Middleware\ModuleACL::class,
        ]);
    })
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
