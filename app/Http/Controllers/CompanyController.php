<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\MedicineResource;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CompanyController extends Controller
{
    public function company(Request $request){
        $company = Company::find($request->id);
        if (!$company){
           return ApiResponse::apiSendResponse(400,'Some company Data Are Missed.',[]);
        }
        return ApiResponse::apiSendResponse(200,"company's medicines Has Been Retrieved Successfully.",MedicineResource::collection($company -> medicines));
    }

    public function Companies(){
        $companies = Company::all();
        return ApiResponse::apiSendResponse(200,'companies Has Been Retrieved Successfully.',CompanyResource::collection($companies));
    }
}
