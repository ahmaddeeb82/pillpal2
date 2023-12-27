<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Company;
use App\Models\Category;
use App\Models\Medicine;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Models\MedicineCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\EditMedicineRequest;
use App\Http\Requests\MedicineRequest;
use App\Http\Resources\AdminCategoryResource;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\MedicineResource;
use App\Http\Resources\MedicineWithoutInfoResource;
use App\Http\Resources\MedicineHomeDashboardResource;

class AdminMedicineController extends Controller
{
    public function addMedicine(MedicineRequest $request){
        $admin_id = auth()->guard('admin')->user()->id;
        $image = $request->file('image');
        $uploadFolder = 'medicines/'. auth()->guard('admin')->user()->id;
        $imagePath = $image->store($uploadFolder, 'public');
        $medicine = Medicine::create([
            'scientific_name' => $request -> scientific_name,
            'commercial_name' => $request -> commercial_name,
            'quantity' => $request -> quantity,
            'price' => $request -> price,
            'expiration_date' => $request -> expiration_date,
            'company_id' => $request -> company_id,
            'admin_id' => $admin_id,
            'image' => $imagePath
        ]);
        $categoy_id = $request -> input('category_id');
        $medicine -> categories() -> attach($categoy_id);
       
        return ApiResponse::apiSendResponse(
            201,
            'Medicine Has Been Created Successfully!',
            'تمت إضافة الدواء بنجاح'
        );
    }
    

    public function homMedicineInfo(){
        $admin_id = auth()->guard('admin')->user()->id;
        $medicines = Medicine::where('admin_id',$admin_id)->get();
        if(count($medicines)==0){
            return ApiResponse::apiSendResponse(
                200,
                'There are no medicines',
                'لا يوجد ادوية'
            );
        }

        return ApiResponse::apiSendResponse(
            200,
            'Medicines data has been retrieved successfully',
            'تم اعادة الادوية بنجاح',
            MedicineHomeDashboardResource::collection($medicines)
        );
    }


    public function editMedicine(EditMedicineRequest $request){
        $admin_id = auth()->guard('admin')->user()->id;
        $medicines = Medicine::where('admin_id', $admin_id)->get();
        $medicine_id = $request->input('medicine_id');
        if(!$medicine_id){
            return ApiResponse::apiSendResponse(
                400,
                'Some Data Are Missed.',
                'بيانات الطلب الذي تقوم به غير مكتملة.'
           );
        }

        $image = $request->file('image');
        $uploadFolder = 'medicines/'. auth()->guard('admin')->user()->id;
        $imagePath = $image->store($uploadFolder, 'public');
        $medicine = $medicines->find($medicine_id);
        if(!$medicine){
            return ApiResponse::apiSendResponse(
                400,
                'Medicine you want to edit is not exist',
                'الدواء الذي تريد تعديل بياناته غير موجودة'
           );
        }
        $medicine -> update([
            'scientific_name' => $request -> scientific_name,
            'commercial_name' => $request -> commercial_name,
            'quantity' => $request -> quantity,
            'price' => $request -> price,
            'expiration_date' => $request -> expiration_date,
            'company_id' => $request -> company_id,
            'admin_id' => $admin_id,
            'image' => $imagePath
        ]);

        return ApiResponse::apiSendResponse(
            200,
            'medicine Has Been Edited Successfully!',
            'تم تعديل الدواء بنجاح'
        );
    }



    public function showMedicineInfo(Request $request){
        $admin_id = auth()->guard('admin')->user()->id;
        $medicine_id = $request->input('medicine_id');
        if(!$medicine_id){
            return ApiResponse::apiSendResponse(
                400,
                'Some medicine Data Are Missed.',
                'بيانات الدواء الذي تقوم به غير مكتملة.'
           );
        }
        $medicine= Medicine::where('id',$medicine_id)->where('admin_id', $admin_id)->first();
        if (!$medicine){
            return ApiResponse::apiSendResponse(
              200,
              'Some medicine Data Are Missed.',
              'بيانات الدواء الذي تقوم به غير مكتملة.'
            );
        }
        return ApiResponse::apiSendResponse(
            200,
            'category data Has Been Retrieved Successfully',
            'تمت إعادة بيانات التصنيف بنجاح',
            new MedicineResource($medicine)
         );
    }



    public function searchByName(Request $request){
        $admin_id = auth()->guard('admin')->user()->id;
        $search = $request -> input('search');
        if (!$search){
            return ApiResponse::apiSendResponse(
                400,
                'You must enter something to search for',
                'يجب ادخال شيء للبحث عنه'
            );
        }
        $results = Medicine::where('scientific_name','like','%'.$search.'%')->where('admin_id', $admin_id)->get();
        $results2 = Category::where('name','like','%'.$search.'%')->where('admin_id', $admin_id)->get();
        if (count($results)==0 && count($results2)==0){
            return ApiResponse::apiSendResponse(
                200,
                'This item was not found',
                'لم يتم العثور على هذا العنصر'
            );
        }
        $finalresults[] = MedicineWithoutInfoResource::collection($results);
        $finalresults[] = AdminCategoryResource::collection($results2);
        return ApiResponse::apiSendResponse(
            200,
            'The data you searched for was successfully returned',
            'تم إرجاع البيانات التي بحثت عنها بنجاح',
            $finalresults
        );
    }



    public function medicinesCounter(){
        $admin_id = auth()->guard('admin')->user()->id;
        $medicines_count = count(Medicine::where('admin_id', $admin_id)->get());
        return ApiResponse::apiSendResponse(
            200,
            'medicines Number Has Been Retrieved Successfully',
            'تم إعادة عدد الادوية بنجاح',
            ['count' => $medicines_count]
        );
    }
}
