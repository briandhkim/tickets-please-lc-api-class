<?php

namespace App\Exceptions\Api\V1;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SingleErrorHandler extends AbstractBaseExceptionHandler
{
    public function createExceptionResponsePayload(Exception $e, Request $request, $status = 0): JsonResponse
    {
        return response()->json([
            'error' => $this->generateErrorArray(intval($e->getCode()), $e->getMessage(), basename(get_class($e))),
        ]);
    }
}
