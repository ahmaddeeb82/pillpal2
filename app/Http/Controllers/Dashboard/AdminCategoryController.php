<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Category;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Requests\EditCategoryRequest;
use App\Http\Resources\AdminCategoryResource;
use App\Http\Resources\DashboardCategoryResource;
use App\Http\Resources\MedicineWithoutInfoResource;

class AdminCategoryController extends Controller
{
    public function addCategory(CategoryRequest $request){
        $admin_id = auth()->guard('admin')->user()->id;
        $image = $request->file('image');
        $uploadFolder = 'categories/'. auth()->guard('admin')->user()->id;
        $imagePath = $image->store($uploadFolder, 'public');
        Category::create([
            'name'=>[ 
                'en' => $request->name_en,
                'ar' => $request->name_ar],
            'admin_id' => $admin_id,
            'image' => $imagePath,
        ]);
        return ApiResponse::apiSendResponse(
            201,
            'Category Has Been Created Successfully!',
            'تمت إضافة التصنيف بنجاح'
        );
    }


    public function categoryInfo(Request $request){
        $admin_id = auth()->guard('admin')->user()->id;
        $category = Category::where('id' ,$request->input('category_id'))->where('admin_id', $admin_id)->get();
        if (!$category){
             return ApiResponse::apiSendResponse(
                 400,
                 'Some category Data Are Missed.',
                 'بيانات التصنيف الذي تقوم به غير مكتملة.'
             );
         }
        
        return ApiResponse::apiSendResponse(
            200,
            'category data Has Been Retrieved Successfully',
            'تمت إعادة بيانات التصنيف بنجاح',
            AdminCategoryResource::collection($category)
        );
    }



    public function showCategories(){
        $admin_id = auth()->guard('admin')->user()->id;
        $categories = Category::where('admin_id',$admin_id)->get();
        if(count($categories)==0){
            return ApiResponse::apiSendResponse(
                200,
                'There are no categories',
                'لا يوجد تصنيفات'
            );
        }
        foreach($categories as $category) {
            $langCategory['id'] = $category->id;
            $langCategory['name_en'] = $category->getTranslations('name')['en'];
            $langCategory['name_ar'] = $category->getTranslations('name')['ar'];
            $langCategory['image'] = $category->image;
            $langCategories[] = $langCategory;
        }


        return ApiResponse::apiSendResponse(
            200,
            'Categories data has been retrieved successfully',
            'تم اعادة التصنيفات بنجاح',
            $langCategories
        );        
    }


    public function editCategory(EditCategoryRequest $request){
        $category_id = $request->input('category_id');
        //$new_data = $request->validated();
        if(!$category_id){
            return ApiResponse::apiSendResponse(
                400,
                'Some category Data Are Missed.',
                'بيانات التصنيف الذي تقوم به غير مكتملة.'
           );
        }
        $image = $request->file('image');
        $uploadFolder = 'categories/'. auth()->guard('admin')->user()->id;
        $imagePath = $image->store($uploadFolder, 'public');
        $category = Category::find($category_id);
        $category->update([
            'name'=>[ 
                'en' => $request->name_en,
                'ar' => $request->name_ar],
            'image' => $imagePath,
        ]);
        return ApiResponse::apiSendResponse(
            200,
            'Category Has Been Edited Successfully!',
            'تم تعديل التصنيف بنجاح'
        );
    }   


    

    public function categoryMedicines(Request $request){
        $admin_id = auth()->guard('admin')->user()->id;
        $category_id = $request->input('category_id');
        if(!$category_id){
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
            MedicineWithoutInfoResource::collection($category->medicines)
         );
    }
}
