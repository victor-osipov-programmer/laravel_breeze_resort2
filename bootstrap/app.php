<?php

use App\Exceptions\GeneralError;
use App\Exceptions\Unauthorized;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
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
            $json = getJSONError($e->message);
            if (count($e->errors) !== 0) {
                $json = [
                    'message' => $e->message,
                    'errors' => $e->errors
                ];
            }

            return response()->json($json, $e->status);
        });

        $exceptions->render(function (GeneralError $e) {
            return response()->json(getJSONError($e->message), $e->status);
        });

        $exceptions->render(function (AccessDeniedHttpException $e) {
            if (Auth::check()) {
                return response()->json(getJSONError('Forbidden'), 403);
            } else {
                return response()->json(getJSONError('Unauthorized'), 401);
            }
        });

        $exceptions->render(function (NotFoundHttpException $e) {
            return response()->json(getJSONError('Not Found'), 404);
        });
    })->create();
