<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\MedicineWithoutInfoResource;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class CategoryController extends Controller
{
    public function category(Request $request){
        $admin_id = $request->header('Str');
        if(!$admin_id) {
            return ApiResponse::apiSendResponse(
                400,
                'Some category Data Are Missed.',
                'بيانات التصنيف الذي تقوم به غير مكتملة.'
           );
        }
        $category_id= $request->category_id;
        if (!$category_id){
            return ApiResponse::apiSendResponse(
                 400,
                 'Some category Data Are Missed.',
                 'بيانات التصنيف الذي تقوم به غير مكتملة.'
            );
           }
        $category= Category::where('id',$category_id)->where('admin_id', $admin_id)->first();
        
        if (!$category){
          return ApiResponse::apiSendResponse(
            200,
            'Some category Data Are Missed.',
            'بيانات التصنيف الذي تقوم به غير مكتملة.'
          );
         }
         return ApiResponse::apiSendResponse(
            200,
            'category data Has Been Retrieved Successfully',
            'تمت إعادة بيانات التصنيف بنجاح',
            MedicineWithoutInfoResource::collection($category->medicines ->where('expired',0))
         );
     }
   
    

     public function allCategories(Request $request){
        $admin_id = $request->header('Str');
        if(!$admin_id) {
            return ApiResponse::apiSendResponse(
                400,
                'Some Data Are Missed.',
                'بيانات الطلب الذي تقوم به غير مكتملة.'
           );
        }
        $categories = Category::where('admin_id', $admin_id)->get();
        if (count($categories)==0){
             return ApiResponse::apiSendResponse(
                200,
                'There are no categories',
                'لا يوجد تصنيفات'
             );
        }
        return ApiResponse::apiSendResponse(
            200,
            'categories Has Been Retrieved Successfully',
            'تمت إعادة التصنيفات بنجاح',
            CategoryResource::collection($categories)
        );
    }


    public function categoriesForHome(Request $request){
        $admin_id = $request->header('Str');
        if(!$admin_id) {
            return ApiResponse::apiSendResponse(
                400,
                'Some Data Are Missed.',
                'بيانات الطلب الذي تقوم به غير مكتملة.'
           );
        }
        $categoriesHome = Category::where('admin_id', $admin_id)->take(4)->get();
        if (count($categoriesHome)==0){
             return ApiResponse::apiSendResponse(
                200,
                'There are no categories',
                'لا يوجد تصنيفات'
             );
        }
        return ApiResponse::apiSendResponse(
            200,
            'categories Has Been Retrieved Successfully',
            'تمت إعادة التصنيفات بنجاح',
            CategoryResource::collection($categoriesHome)
        );
    }
}
