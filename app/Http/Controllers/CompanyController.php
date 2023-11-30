<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\MedicineResource;
use App\Http\Resources\MedicineWithoutInfoResource;

class CompanyController extends Controller
{
    public function company(Request $request){
        $company_id = $request->company_id;
        $company = Company::find($request->company_id);
        if (!$company_id){      
             return ApiResponse::apiSendResponse(
                400,
                'Some company Data Are Missed.',
                'بيانات الشركة الذي تقوم به غير مكتملة.',
                []
             );
        }
        if (!$company){
            return ApiResponse::apiSendResponse(
                200,
                'Some company Data Are Missed.',
                'بيانات الشركة الذي تقوم به غير مكتملة.',
                []
             );
        }
        return ApiResponse::apiSendResponse(
            200,
            'company data Has Been Retrieved Successfully',
            'تمت إعادة بيانات الشركة بنجاح',
            MedicineWithoutInfoResource::collection($company -> medicines ->where('expired',0))
        );
    }

    public function Companies(){
        $companies = Company::all();
        if (!$companies){
             return ApiResponse::apiSendResponse(
                200,
                'There are no companies',
                'لا يوجد شركات',
                []
             );
        }
        return ApiResponse::apiSendResponse(
            200,
            'companies Has Been Retrieved Successfully',
            'تمت إعادة الشركات بنجاح',
            CompanyResource::collection($companies)
        );
    }
    public function companyForHome(Request $request){
        $companyHome = Company::take(4)->get();
        if (!$companyHome){
             return ApiResponse::apiSendResponse(
                200,
                'There are no companies',
                'لا يوجد شركات',
                []
             );
        }
        return ApiResponse::apiSendResponse(
            200,
            'companies Has Been Retrieved Successfully',
            'تمت إعادة الشركات بنجاح',
            CompanyResource::collection($companyHome)
        );
    }
}
