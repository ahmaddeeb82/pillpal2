<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\CompnayRequest;
use App\Models\Company;
use Illuminate\Http\Request;

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
}
