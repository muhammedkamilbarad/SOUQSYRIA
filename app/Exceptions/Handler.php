<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Database\QueryException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception)
    {
        // Handle Not Found URL (404)
        if ($exception instanceof NotFoundHttpException) {
            return response()->json([
                'status' => false,
                'message' => 'URL not found',
            ], 404);
        }
        //Handle Not Found Data (ModelNotFoundException)
        if ($exception instanceof ModelNotFoundException) {
            return response()->json([
                'status' => false,
                'message' => 'Resource not found',
            ], 404);
        }

        // Handle Method Not Allowed (405)
        if ($exception instanceof MethodNotAllowedHttpException) {
            return response()->json([
                'status' => false,
                'message' => 'Method not allowed',
            ], 405);
        }

        // Handle Validation Errors (400 Bad Request)
        if ($exception instanceof ValidationException) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $exception->errors(),
            ], 400);
        }

        if ($exception instanceof QueryException) {
            if ($exception->errorInfo[1] == 1451) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unable to delete the item because it is linked to other records. Please remove the associated records first.',
                ], 400);
            }}

        return parent::render($request, $exception);
    }
    public function unauthenticated($request, AuthenticationException $exception)
    {
        return response()->json([
            'status' => false,
            'message' => 'Unauthenticated error, Please login to access this route',
        ], 401);
    }
}
