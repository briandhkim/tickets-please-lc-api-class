<?php

namespace App\Exceptions\Api\V1;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ModelNotFoundHandler extends AbstractBaseExceptionHandler
{
    public function createExceptionResponsePayload(Exception|ModelNotFoundException $e, Request $request, $status = 404): JsonResponse
    {
        return response()->json([
            'error' => $this->generateErrorArray($status, 'Not found '.$request->getRequestUri(), basename(get_class($e))),
        ]);
    }
}
