<?php

namespace App\Trait;

trait ApiResponse
{

    public function successResponse($code, $data, $message = null): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'attribute' => $data,
        ], $code);
    }

    public function errorResponse($code, $message = null): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
        ], $code);
    }

}
