<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        $middleware->alias([
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
            'superadmin' => \App\Http\Middleware\IsSuperAdmin::class,
            'admin' => \App\Http\Middleware\IsAdmin::class,
            'teacher' => \App\Http\Middleware\IsTeacher::class,
            'student' => \App\Http\Middleware\IsStudent::class,
            'parent' => \App\Http\Middleware\IsParent::class,
            'driver' => \App\Http\Middleware\IsDriver::class,
            'staff' => \App\Http\Middleware\IsStaff::class,
            'checkpermission' => \App\Http\Middleware\CheckPermission::class,
        ]);

        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

