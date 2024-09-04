<?php

use App\Exceptions\GeneralError;
use App\Exceptions\Unauthorized;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        apiPrefix: 'resortapi',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->shouldRenderJsonWhen(fn() => true);

        function getJSONError($message)
        {
            return [
                'error' => [
                    'message' => $message
                ]
            ];
        }

        $exceptions->render(function (Unauthorized $e) {
            // dd($e);
            return response()->json([
                'message' => 'Unauthorized',
                'errors' => [
                    'login' => 'invalid credentials'
                ]
            ], $e->status);
        });

        $exceptions->render(function (GeneralError $e) {
            return response()->json(getJSONError($e->message), $e->status);
        });
    })->create();
