<?php

namespace Mhasnainjafri\APIToolkit\API;

use Illuminate\Http\JsonResponse;
use Mhasnainjafri\APIToolkit\logger\FileLogger;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;

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


        $response = response()->json([
            'msg' => $msg,
            'data' => $data,
            'statusCode' => $statusCode,
            'meta' => $meta
        ], $statusCode);

        self::saveLogs(request(),$response);
        return $response;
    }


    public static function saveLogs(Request $request, Response|JsonResponse|RedirectResponse $response)
    {
        if(config('apitoolkit.logger')){
        try{
        $fileLogger = new FileLogger();
        $fileLogger->saveLogs($request, $response);
        }catch (\Exception $e){
        \Log::error($e->getMessage());
        }
        }
    }




}
