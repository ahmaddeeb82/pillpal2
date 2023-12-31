<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use App\Models\Medicine;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\MedicineResource;
use App\Http\Resources\MedicineWithoutInfoResource;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class MedicineController extends Controller
{
    public function medicineInfo(Request $request){
        $admin_id = $request->header('Str');
        if(!$admin_id) {
            return ApiResponse::apiSendResponse(
                400,
                'Some Data Are Missed.',
                'بيانات الطلب الذي تقوم به غير مكتملة.'
           );
        }
        if (!($request -> expired)){
            $medicine = Medicine::where('id' ,$request->input('medicine_id'))->where('admin_id', $admin_id)->get();
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
                 MedicineResource::collection($medicine)
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




    
    public function userFavorites(Request $request){
        $admin_id = $request->header('Str');
        if(!$admin_id) {
            return ApiResponse::apiSendResponse(
                400,
                'Some Data Are Missed.',
                'بيانات الطلب الذي تقوم به غير مكتملة.'
           );
        }
        $user = auth()->user();
        if (count($user->medicines->where('admin_id', $admin_id))==0){
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
            MedicineWithoutInfoResource::collection($user->medicines->where('admin_id', $admin_id))
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




    public function searchByName(Request $request){
        $admin_id = $request->header('Str');
        if(!$admin_id) {
            return ApiResponse::apiSendResponse(
                400,
                'Some Data Are Missed.',
                'بيانات الطلب الذي تقوم به غير مكتملة.'
           );
        }
        $seach = $request->input('search');
        if (!$seach){
            return ApiResponse::apiSendResponse(
                400,
                'You must enter something to search for',
                'يجب ادخال شيء للبحث عنه'
            );
        }
        $results = Medicine::where('scientific_name','like','%'.$seach.'%')->where('admin_id', $admin_id)->get();
        $results2 = Category::where('name->'. LaravelLocalization::getCurrentLocale(),'like','%'.$seach.'%')->where('admin_id', $admin_id)->get();
        if (count($results)==0 && count($results2)==0){
            return ApiResponse::apiSendResponse(
                200,
                'This item was not found',
                'لم يتم العثور على هذا العنصر'
            );
        }
        $finalresults['medicines'] = MedicineWithoutInfoResource::collection($results);
        $finalresults['categories'] = CategoryResource::collection($results2);
        return ApiResponse::apiSendResponse(
            200,
            'The data you searched for was successfully returned',
            'تم إرجاع البيانات التي بحثت عنها بنجاح',
            $finalresults
        );
    }
}
