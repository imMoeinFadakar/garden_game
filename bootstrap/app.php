<?php

use App\Http\Middleware\Managment;
use App\Http\Middleware\CheckUserStatus; // ← اطمینان از این خط
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
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(Managment::class);
        $middleware->alias([
            'check.user.status' => CheckUserStatus::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->withSchedule(function (\Illuminate\Console\Scheduling\Schedule $schedule) {
    $schedule->command('tokens:delete-expired')->daily();
    })
    ->create();
