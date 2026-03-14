<?php

namespace App\Http\Helpers;

class Response
{
    public static function success(string $message = 'success')
    {
        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }

    public static function successWithData(
        mixed $data,
        string $message = 'success'
    ) {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data
        ]);
    }

    public static function error(
        string $message = 'Internal Server Error',
        int $statusCode = 500
    ) {
        return response()->json([
            'status' => false,
            'message' => $message
        ], $statusCode);
    }

    public static function errorWithData(
        mixed $data,
        string $message = 'Internal Server Error',
        int $statusCode = 500
    ) {
        return response()->json([
            'status' => false,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }
}
