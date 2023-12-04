<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Resources\MedicineResource;
use App\Http\Resources\MedicineWithoutInfoResource;
use App\Models\Medicine;
use App\Models\User;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function medicineInfo(Request $request){
        if (!($request -> expired)){
            $medicine = Medicine::where('id' ,$request->input('medicine_id'))->first();
            if (!$medicine){
                return ApiResponse::apiSendResponse(
                    400,
                    'Some medicine Data Are Missed.',
                    'بيانات الدواء الذي تقوم به غير مكتملة.'
                );
            }
            return ApiResponse::apiSendResponse(
                200,
                'medicine data Has Been Retrieved Successfully',
                'تمت إعادة بيانات الدواء بنجاح',
                new MedicineResource($medicine)
            );
        }
        return ApiResponse::apiSendResponse(
            200,
            'This medicine has expired.',
            ' لقد انتهت صلاحية هذا الدواء'
        );
    }



    public function addFavorite(Request $request){
        $user_id = auth()->user()->id;
        $medicine_id = $request->input('medicine_id');
        if (!$medicine_id){
            return ApiResponse::apiSendResponse(
                400,
                'Some medicine Data Are Missed.',
                'بيانات الدواء الذي تقوم به غير مكتملة.'
            );
        }
        $user = User::find($user_id);
        $user ->medicines()->attach($medicine_id);
        return ApiResponse::apiSendResponse(
            200,
            'Medicine Has Been Added to favorite Successfully',
            'تم اضافة الدواء الى المفضلة بنجاح'
        );
    }




    
    public function userFavorites(){
        $user = auth()->user();
        if (count($user->medicines)==0){
            return ApiResponse::apiSendResponse(
                200,
                'There are no favorite medications',
                'لا يوجد ادوية مفضلة'
            );
        }
        return ApiResponse::apiSendResponse(
            200,
            'Favorite data has been Retrieved successfully',
            'تمت اعادة البيانات المفضلة بنجاح',
            MedicineWithoutInfoResource::collection($user->medicines)
        );
    }



    public function deleteFavorite(Request $request){
        $user_id = auth()->user()->id;
        $medicine_id = $request->input('medicine_id');
        if (!$medicine_id){
            return ApiResponse::apiSendResponse(
                400,
                'Some medicine Data Are Missed.',
                'بيانات الدواء الذي تقوم به غير مكتملة.'
            );
        }
        $user = User::find($user_id);
        $user ->medicines()->detach($medicine_id);
        return ApiResponse::apiSendResponse(
            200,
            'Medicine Has Been Deleted from favorite Successfully',
            'تم حذف الدواء من المفضلة بنجاح'
        );
    }
}
