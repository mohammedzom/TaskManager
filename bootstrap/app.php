<?php

use App\Exceptions\ApiException;
use App\Http\Middleware\CheckUserRole;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'CheckUser' => CheckUserRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        $exceptions->render(function (ModelNotFoundException $e, Request $request) {
            if ($request->is('api/*')) {
                $modelName = class_basename($e->getModel());

                return (new ApiException("No results found for {$modelName}.", '', 404))->render($request);
            }
        });
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return (new ApiException('The resource you are looking for could not be found.', $e, 404))->render($request);
            }
        });

        $exceptions->render(function (AccessDeniedHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return (new ApiException('You are not authorized to perform this action.', $e, 403))->render($request);
            }
        });

        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return (new ApiException('Unauthenticated. Please log in first.', $e, 401))->render($request);
            }
        });
        $exceptions->render(function (UniqueConstraintViolationException $e, Request $request) {
            if ($request->is('api/*')) {
                return (new ApiException(
                    'Conflict: The entry already exists in the database.',
                    $e->getMessage(),
                    409
                ))->render($request);
            }
        });

    })->create();
