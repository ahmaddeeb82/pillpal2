<?php

namespace App\Helpers;

class ApiResponse {
    public static function apiSendResponse($code = 200, $message = null, $data = []) {
        return response()->json([
            "code"=> $code,
            "message"=> $message,
            "data"=> $data,
        ],$code);
    }
}