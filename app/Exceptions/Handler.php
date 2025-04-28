<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        // Handle 404 for API routes
        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Resource Not Found',
                    'messages' => $e->getMessage(),
                ], 404);
            }
        });
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof \Illuminate\Session\TokenMismatchException && $request->is('api/*')) {
            return response()->json([
                'success' => false,
                'message' => 'CSRF token mismatch.',
            ], 419);
        }

        return parent::render($request, $e);
    }
    // Optional: cleaner unauthenticated response
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->is('api/*')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
                'code' => 'unauth',
            ], 401);
        }

        return parent::unauthenticated($request, $exception);
    }
}
