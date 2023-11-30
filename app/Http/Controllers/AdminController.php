<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\AuthLoginRequest;
use App\Http\Resources\AuthResource;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function login(AuthLoginRequest $request) {
        $credentials = $request->validated();

        if (auth()->attempt(['phone' => $credentials['phone'], 'password' => $credentials['password']])) {
            $data['token'] = auth()->user()->createToken('testProject')->accessToken;
            $data['id'] = auth()->user()->id;
            return ApiResponse::apiSendResponse(
                200,
                'User is logged in successfully!',
                'تم تسجيل الدخول بنجاح',
                new AuthResource($data)
            );
        } else {
            return ApiResponse::apiSendResponse(
                401,
                'Please Check Sign in Information And Try Again.',
                'الرجاء التحقق من معلومات تسجيل الدخول والمحاولة مرة أخرى'
            );
        }
    }

    public function logout(Request $request)
    {
        if ($request->user()) {
            $token = $request->user()->token();
            $token->revoke();

            return ApiResponse::apiSendResponse(
                200,
                'Logged out successfully!',
                'تم تسجيل الخروج بنجاح'
            );
        }
        return ApiResponse::apiSendResponse(
            401,
            'Unauthorized',
            'لا يوجد صلاحيات'
        );
    }


}
