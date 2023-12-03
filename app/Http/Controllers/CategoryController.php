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
        $category_id= $request->category_id;
        if (!$category_id){
            return ApiResponse::apiSendResponse(
                 400,
                 'Some category Data Are Missed.',
                 'بيانات التصنيف الذي تقوم به غير مكتملة.'
            );
           }
        $category= Category::where('id',$category_id)->first();
        
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
   
    

     public function allCategories(){
        $categories = Category::all();
        if (!$categories){
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
        $categoriesHome = Category::take(4)->get();
        if (!$categoriesHome){
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
