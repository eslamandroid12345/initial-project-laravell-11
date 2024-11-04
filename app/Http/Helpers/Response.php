<?php

namespace App\Http\Helpers;

class Response
{
    public static function success($status = Http::OK, $message = 'Success', $data = []) {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $status);
    }

    public static function fail($status = Http::UNPROCESSABLE_ENTITY, $message = 'Error', $data = []) {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $status);
    }

    public static function custom($status, $message, $data = []) {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $status);
    }
}
