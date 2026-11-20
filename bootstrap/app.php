<?php

use App\Http\Middleware\TrackVisitorActivity;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            TrackVisitorActivity::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

// On the live cPanel host the Laravel folder IS the web root: index.php and the
// public assets (images/, css/, logo/ ...) sit next to app/ instead of inside
// public/. Point public_path() there so admin uploads on the "site" disk land
// where the browser can load them (web requests and artisan alike).
if (is_file($app->basePath('index.php'))) {
    $app->usePublicPath($app->basePath());
}

return $app;
