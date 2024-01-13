<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Helpers\ApiResponse;
use App\Http\Requests\AdminRgisterRequest;
use Illuminate\Http\Request;
use App\Http\Resources\AuthResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthRegisterRequest;
use App\Models\AdminToken;

class AdminController extends Controller
{
    public function login(AuthLoginRequest $request) {
        $credentials = $request->validated();

        if (auth()->guard('admin_ses')->attempt(['phone' => $credentials['phone'], 'password' => $credentials['password']])) {
            $data['token'] = auth()->guard('admin_ses')->user()->createToken('testProject')->accessToken;
            $data['id'] = auth()->guard('admin_ses')->user()->id;
            AdminToken::create([
                'device_token' => $request->device_token,
                'admin_id' => $data['id']
            ]);
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

    public function superLogin(Request $request) {
        $credentials = $request->all();

        if (auth()->guard('superadmin_ses')->attempt(['phone' => $credentials['phone'], 'password' => $credentials['password']])) {
            $data['token'] = auth()->guard('superadmin_ses')->user()->createToken('testProject')->accessToken;
            $data['id'] = auth()->guard('superadmin_ses')->user()->id;
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


    public function addAdmin(AdminRgisterRequest $request) {

        $credentials = $request->validated();

        data_forget($credentials, 'confirm_password');

        $credentials['password'] = Hash::make($credentials['password']);

        $user = Admin::create($credentials);

        $data['token'] = $user->createToken('testProject')->accessToken;

        $data['id'] = $user->id;

        return ApiResponse::apiSendResponse(
            201,
            'User informations has been successfully registered.',
            'تم تسجيل بيانات المستخدم بنجاح',
            new AuthResource($data)
        );
    }
}
