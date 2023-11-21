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
    public function register(AuthRegisterRequest $request) {

        $credentials = $request->validated();

        data_forget($credentials,'confirm_password');

        $credentials['password'] = Hash::make($credentials['password']);

        $user = User::create($credentials);

        $data['token'] = $user->createToken('testProject')->accessToken;

        $data['id'] = $user->id;

        if(LaravelLocalization::getCurrentLocale() == 'ar') {
            $message = 'تم تسجيل بيانات المستخدم بنجاح';
        }
        else {
            $message = 'User informations has been successfully registered.';
        }

        return ApiResponse::apiSendResponse(201,$message,new AuthResource($data));
    }


    public function login(AuthLoginRequest $request) {
        
        $credentials = $request->validated();

        if(auth()->attempt(['phone'=>$credentials['phone'] , 'password' => $credentials['password']])) {
            $data['token'] = auth()->user()->createToken('testProject')->accessToken;
            $data['id'] = auth()->user()->id;
            if(LaravelLocalization::getCurrentLocale() == 'ar') {
                $message = 'تم تسجيل الدخول بنجاح';
            }
            else {
                $message = '‘User is logged in successfully!';
            }
            return ApiResponse::apiSendResponse(200,$message,new AuthResource($data));
        }
        
        else {

            if(LaravelLocalization::getCurrentLocale() == 'ar') {
                $message = 'الرجاء التحقق من معلومات تسجيل الدخول والمحاولة مرة أخرى';
            }
            else {
                $message = 'Please Check Sign in Information And Try Again.';
            }

            return ApiResponse::apiSendResponse(401,$message);
        }
    }

    public function logout(Request $request)
{
    if ($request->user()) {
        $token = $request->user()->token();
        $token->revoke();
        if(LaravelLocalization::getCurrentLocale() == 'ar') {
            $message = 'تم تسجيل الخروج بنجاح';
        }
        else {
            $message = 'Logged out successfully!';
        }
        return ApiResponse::apiSendResponse(200, $message);
    }

    if(LaravelLocalization::getCurrentLocale() == 'ar') {
        $message = 'لا يوجد صلاحيات';
    }
    else {
        $message = 'Unauthorized';
    }
    return ApiResponse::apiSendResponse(401, $message);
}

}
