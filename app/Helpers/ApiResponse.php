<?php

namespace App\Helpers;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class ApiResponse {
    public static function apiSendResponse($code = 200, $message1 = null,$message2 = null, $data = []) {
        if(LaravelLocalization::getCurrentLocale() == 'ar') {
            return response()->json([
                "code"=> $code,
                "message"=> $message2,
                "data"=> $data,
            ],$code);
        }
        else {
            return response()->json([
                "code"=> $code,
                "message"=> $message1,
                "data"=> $data,
            ],$code);
        }
    }
}