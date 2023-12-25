<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class AdminCategoryController extends Controller
{
    public function addCategory(CategoryRequest $request){
        $admin_id = auth()->guard('admin')->user()->id;
        $image = $request->file('image');
        $uploadFolder = 'categories/'. auth()->guard('admin')->user()->id;
        $imagePath = $image->store($uploadFolder, 'public');
        Category::create([
            'name'=>[ 
                'en' => $request->name,
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

        return ApiResponse::apiSendResponse(
            200,
            'Categories data has been retrieved successfully',
            'تم اعادة التصنيفات بنجاح',
            CategoryResource::collection($categories)
        );        
    }

    public function editCategory(CategoryRequest $request){
        $admin_id = auth()->guard('admin')->user()->id;
        $categories = Category::where('admin_id', $admin_id)->get();
        $category_id = $request->input('category_id');

        }
}
