<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthRegisterRequest;
use App\Http\Resources\AuthResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;


class AuthController extends Controller
{
    public function register(AuthRegisterRequest $request)
    {

        $credentials = $request->validated();

        data_forget($credentials, 'confirm_password');

        $credentials['password'] = Hash::make($credentials['password']);

        $user = User::create($credentials);

        $data['token'] = $user->createToken('testProject')->accessToken;

        $data['id'] = $user->id;

        return ApiResponse::apiSendResponse(
            201,
            'User informations has been successfully registered.',
            'تم تسجيل بيانات المستخدم بنجاح',
            new AuthResource($data)
        );
    }


    public function login(AuthLoginRequest $request)
    {

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
