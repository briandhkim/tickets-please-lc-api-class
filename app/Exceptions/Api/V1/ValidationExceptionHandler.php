<?php

namespace App\Exceptions\Api\V1;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ValidationExceptionHandler extends AbstractBaseExceptionHandler
{
    public function createExceptionResponsePayload(Exception|ValidationException $e, Request $request, $status = 422): JsonResponse
    {
        $errors = [];

        foreach ($e->errors() as $key => $value) {
            foreach ($value as $message) {
                $errors[] = $this->generateErrorArray($status, $message, null, $key);
            }
        }

        return response()->json([
            'error' => $errors,
        ]);
    }
}
