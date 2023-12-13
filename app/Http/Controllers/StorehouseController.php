<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Resources\StorehouseResource;
use App\Models\Admin;
use Illuminate\Http\Request;

class StorehouseController extends Controller
{
    public function storehouses(Request $request) {
        $storeHouses = Admin::all();
        return ApiResponse::apiSendResponse(
            200,
            'Storehouses Has Been Retrieved Successfully',
            'تمت إعادة المستودعات بنجاح',
            StorehouseResource::collection($storeHouses)
        );
    }
}
