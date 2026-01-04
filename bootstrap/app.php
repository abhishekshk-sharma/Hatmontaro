<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin_auth' => \App\Http\Middleware\AdminAuth::class,
            'enhanced.payment.security' => \App\Http\Middleware\EnhancedPaymentSecurity::class,
        ]);
        $middleware->web(append: [
            \App\Http\Middleware\TrackVisitor::class,
        ]);
        // Ensure CSRF protection is enabled for web routes
        $middleware->validateCsrfTokens(except: [
            '/payment/webhook', // Exclude webhook from CSRF
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
