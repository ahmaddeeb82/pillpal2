<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\MedicineRequest;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\MedicineHomeDashboardResource;
use App\Models\Company;
use App\Models\Medicine;
use App\Models\MedicineCategory;
use Illuminate\Http\Request;

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
}
