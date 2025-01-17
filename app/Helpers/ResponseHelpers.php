<?php

use Illuminate\Http\JsonResponse;

if (!function_exists('successResponse')) {
    function successResponse($data = [], $message = '', $code = 200): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => $data
        ], $code);
    }
}

if (!function_exists('failureResponse')) {
    function failureResponse($message = '', $code = 403): JsonResponse
    {
        return response()->json([
            'message' => $message
        ], $code);
    }
}