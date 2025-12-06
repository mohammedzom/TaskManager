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
use Illuminate\Validation\ValidationException;
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

        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {

                if ($e->getPrevious() instanceof ModelNotFoundException) {
                    $modelName = class_basename($e->getPrevious()->getModel());

                    return (new ApiException(
                        "No results found for {$modelName}.",
                        null,
                        404,
                        'MODEL_NOT_FOUND'
                    ))->render($request);
                }

                return (new ApiException(
                    'The resource or endpoint you are looking for could not be found.',
                    null,
                    404,
                    'ENDPOINT_NOT_FOUND'
                ))->render($request);
            }
        });

        $exceptions->render(function (ValidationException $e, Request $request) {
            if ($request->is('api/*')) {
                return (new ApiException(
                    'Validation Error',
                    $e->errors(),
                    422,
                    'VALIDATION_ERROR'
                ))->render($request);
            }
        });

        $exceptions->render(function (AccessDeniedHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return (new ApiException(
                    'You are not authorized to perform this action.',
                    null,
                    403,
                    'FORBIDDEN'
                ))->render($request);
            }
        });

        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return (new ApiException(
                    'Unauthenticated. Please log in first.',
                    null,
                    401,
                    'UNAUTHENTICATED'
                ))->render($request);
            }
        });

        $exceptions->render(function (UniqueConstraintViolationException $e, Request $request) {
            if ($request->is('api/*')) {
                return (new ApiException(
                    'Conflict: The entry already exists in the database.',
                    $e->getMessage(),
                    409,
                    'CONFLICT'
                ))->render($request);
            }
        });

        $exceptions->render(function (Throwable $e, Request $request) {
            if ($request->is('api/*')) {
                return (new ApiException(
                    app()->isLocal() ? $e->getMessage() : 'Something went wrong.',
                    app()->isLocal() ? $e->getTrace() : null, // التفاصيل للمطور فقط
                    500,
                    'SERVER_ERROR'
                ))->render($request);
            }
        });

    })->create();
