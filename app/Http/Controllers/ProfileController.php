<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\AuthRegisterRequest;
use App\Http\Requests\EditProfileRequest;
use App\Http\Requests\ImageRequest;
use App\Http\Requests\PasswordRequest;
use App\Http\Resources\AuthResource;
use App\Http\Resources\ProfileResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\TokenRepository;
use Laravel\Passport\Token;

class ProfileController extends Controller
{

    function editInfo(EditProfileRequest $request)
    {

        $info = $request->validated();
        if (!$info) {
            return ApiResponse::apiSendResponse(
                400,
                'Some Order Data Are Missed.',
                'بيانات الطلب الذي تقوم به غير مكتملة.'
            );
        }
        $user = auth()->user();
        $auth = false;
        if ($info['phone'] != $user->phone) {
            $auth = true;
        }
        $user->update($info);

        if ($auth) {
            $tokenRepository = app(TokenRepository::class);
            $tokens = Token::where('user_id', $user->id)->get();
            foreach ($tokens as $token) {
                $tokenRepository->revokeAccessToken($token->id);
            }

            $newToken = $user->createToken('testProject')->accessToken;
            $response['id'] = $user->id;
            $response['token'] = $newToken;

            return ApiResponse::apiSendResponse(
                200,
                'User informations has been successfully edited.',
                'تم تعديل بيانات المستخدم بنجاح',
                new AuthResource($response)
            );
        }


        return ApiResponse::apiSendResponse(
            200,
            'User informations has been successfully edited.',
            'تم تعديل بيانات المستخدم بنجاح',
        );
    }

    public function getInfo()
    {
        $user = auth()->user();

        return ApiResponse::apiSendResponse(
            200,
            'User informations has been successfully retrieved.',
            'تمت إعادة بيانات المستخدم بنجاح',
            new ProfileResource($user)
        );
    }

    public function setImage(ImageRequest $request)
    {
        $image = $request->file('image');
        if (empty($image)) {
            return ApiResponse::apiSendResponse(
                400,
                'Some Order Data Are Missed.',
                'بيانات الطلب الذي تقوم به غير مكتملة.'
            );
        }
        $user = auth()->user();
        $uploadFolder = 'ass' . auth()->user()->id . 'zz';
        $imagePath = $image->store($uploadFolder, 'public');
        $user->update([
            'image' => $imagePath,
        ]);

        return ApiResponse::apiSendResponse(
            200,
            'User Image Has Been Updated Successfully',
            'تم تحديث صورة المستخدم بنجاح',
            new ProfileResource($user)
        );
    }

    public function deleteImage()
    {

        $user = auth()->user();

        $user->update([
            'image' => null,
        ]);

        return ApiResponse::apiSendResponse(
            200,
            'User Image Has Been Deleted Successfully',
            'تم حذف صورة المستخدم بنجاح',
            new ProfileResource($user)
        );
    }

    public function changePassword(PasswordRequest $request)
    {
        $data = $request->validated();
        $user = auth()->user();
        if (!Hash::check($data['old_password'], $user->password)) {
            return ApiResponse::apiSendResponse(
                401,
                'Old Password Is Incorrect',
                'كلمة السر القديمة غير صحيحة',
            );
        }

        $user->update([
            'password' => Hash::make($data['new_password']),
        ]);

        $tokenRepository = app(TokenRepository::class);
        $tokens = Token::where('user_id', $user->id)->get();
            foreach ($tokens as $token) {
                $tokenRepository->revokeAccessToken($token->id);
        }

        $newToken = $user->createToken('testProject')->accessToken;
        $response['id'] = $user->id;
        $response['token'] = $newToken;

        return ApiResponse::apiSendResponse(
            200,
            'Password Changed Successfully',
            'تم تغيير كلمة المرور بنجاح',
            new AuthResource($response)
        );
    }
}
