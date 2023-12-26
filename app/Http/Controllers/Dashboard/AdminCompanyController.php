<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Company;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CompnayRequest;
use App\Http\Resources\CompanyResource;

use function PHPUnit\Framework\isEmpty;
use App\Http\Resources\MedicineWithoutInfoResource;

class AdminCompanyController extends Controller
{
    public function addCompany(CompnayRequest $request) {
        $admin_id = auth()->guard('admin')->user()->id;
        Company::create([
            'name' => $request->name,
            'admin_id' => $admin_id,
        ]);

        return ApiResponse::apiSendResponse(
            201,
            'Company Has Been Created Successfully!',
            'تمت إضافة الشركة بنجاح'
        );
    }

    public function showCompanies(){
        $admin_id = auth()->guard('admin')->user()->id;
        $companies = Company::where('admin_id', $admin_id)->get();
        if(count($companies)==0){
            return ApiResponse::apiSendResponse(
                200,
                'There are no Companies',
                'لا يوجد شركات'
            );
        }
        
        return ApiResponse::apiSendResponse(
            200,
            'Companies data has been Retrieved successfully',
            'تم اعادة الشركات بنجاح',
            CompanyResource::collection($companies)
        );
    }


    public function companyInfo(Request $request){
        $admin_id = auth()->guard('admin')->user()->id;
        $company_id = $request->input('company_id');
        if(!$company_id){
            return ApiResponse::apiSendResponse(
                400,
                'Some company Data Are Missed.',
                'بيانات الشركة الذي تقوم به غير مكتملة.'
           );
        }
        $company= Company::where('id',$company_id)->where('admin_id', $admin_id)->first();
        if (!$company){
             return ApiResponse::apiSendResponse(
                 400,
                 'Some company Data Are Missed.',
                 'بيانات الشركة الذي تقوم به غير مكتملة.'
             );
         }
        
        return ApiResponse::apiSendResponse(
            200,
            'company data Has Been Retrieved Successfully',
            'تمت إعادة بيانات الشركة بنجاح',
             CompanyResource::collection($company)
            
        );
    }


    public function editCompanyName(CompnayRequest $request){
        $admin_id = auth()->guard('admin')->user()->id;
        $companies = Company::where('admin_id', $admin_id)->get();
        $company_id = $request->input('company_id');
        $new_name = $request->input('name');

        if(!$new_name || !$company_id){
            return ApiResponse::apiSendResponse(
                400,
                'Some Data Are Missed.',
                'بيانات الطلب الذي تقوم به غير مكتملة.'
           );
        }
        $company = $companies-> find($company_id);
        $company ->update(['name'=> $new_name]);

        return ApiResponse::apiSendResponse(
            200,
            'company name has been successfully edited.',
            'تم تعديل اسم الشركة بنجاح'
        );
    }



    public function companyMedicines(Request $request){
        $admin_id = auth()->guard('admin')->user()->id;
        $company_id = $request->input('company_id');
        if(!$company_id){
            return ApiResponse::apiSendResponse(
                400,
                'Some company Data Are Missed.',
                'بيانات الشركة الذي تقوم به غير مكتملة.'
           );
        }
        $company= Company::where('id',$company_id)->where('admin_id', $admin_id)->first();
        if (!$company){
            return ApiResponse::apiSendResponse(
              200,
              'Some company Data Are Missed.',
              'بيانات الشركة الذي تقوم به غير مكتملة.'
            );
        }
        return ApiResponse::apiSendResponse(
            200,
            'company data Has Been Retrieved Successfully',
            'تمت إعادة بيانات الشركة بنجاح',
            MedicineWithoutInfoResource::collection($company->medicines)
         );
    }
}
