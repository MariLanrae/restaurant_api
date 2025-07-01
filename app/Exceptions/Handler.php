<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $e): JsonResponse|Response
    {
        if ($e instanceof ValidationException) {
            return response()->json(
                [
                    'message' => 'The given data was invalid.',
                    'errors' => $e->errors(),
                ], 422);
        }

        if ($e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException) {
            return response()->json(
                [
                    'message' => 'Resource not found.',
                ], 404);
        }

        if ($e instanceof AuthorizationException) {
            return response()->json(
                [
                    'message' => $e->getMessage(),
                ], 403);
        }

        if ($e instanceof AuthenticationException) {
            return response()->json(
                [
                    'message' => $e->getMessage(),
                ], 401);
        }

        if ($e instanceof Exception) {
            $code = $e->getCode();
            $httpCode = (is_int($code) && $code >= 100 && $code <= 599) ? $code : 500;

            return response()->json(
                [
                    'message' => $e->getMessage(),
                ],
                $httpCode
            );
        }

        return parent::render($request, $e);
    }
}
