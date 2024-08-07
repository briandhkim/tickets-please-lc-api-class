<?php

namespace App\Exceptions\Api\V1;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class AbstractBaseExceptionHandler
{
    public static array $handlers = [
        AuthenticationException::class => AuthenticationExceptionHandler::class,
        AccessDeniedHttpException::class => AuthenticationExceptionHandler::class,
        ValidationException::class => ValidationExceptionHandler::class,
        ModelNotFoundException::class => ModelNotFoundHandler::class,
        NotFoundHttpException::class => ModelNotFoundHandler::class,
    ];

    protected function generateErrorArray(int $status, string $message, ?string $type = null, ?string $source = null)
    {
        $error = [
            'status' => $status,
            'message' => $message,
        ];

        if ($type) {
            $error['type'] = $type;
        }
        if ($source) {
            $error['source'] = $source;
        }

        return $error;
    }

    abstract public function createExceptionResponsePayload(Exception $e, Request $request, $status = 0): JsonResponse;
}
