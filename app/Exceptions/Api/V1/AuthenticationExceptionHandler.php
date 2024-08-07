<?php

namespace App\Exceptions\Api\V1;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthenticationExceptionHandler extends AbstractBaseExceptionHandler
{
    public function createExceptionResponsePayload(Exception|AuthenticationException $e, Request $request, $status = 401): JsonResponse
    {
        // log auth error
        $source = 'Line: '.$e->getLine().', File: '.$e->getFile();
        Log::notice(basename(get_class($e)).' - '.$e->getMessage().' - '.$source);

        return response()->json([
            'error' => $this->generateErrorArray($status, $e->getMessage(), basename(get_class($e))),
        ]);
    }
}
