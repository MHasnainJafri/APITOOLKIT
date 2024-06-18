<?php

namespace Mhasnainjafri\APIToolkit\API;

use Illuminate\Http\JsonResponse;

class APIHelper
{
    /**
     * Format the response.
     *
     * @param array $data
     * @param string $msg
     * @param int $statusCode
     * @return JsonResponse
     */
    public static function formatResponse($data, $msg, $statusCode,$meta=[]): JsonResponse
    {
        return response()->json([
            'msg' => $msg,
            'data' => $data,
            'statusCode' => $statusCode,
            'meta' => $meta
        ], $statusCode);
    }
}
