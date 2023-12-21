<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderMedicineResource;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function adminOrders(Request $request)
    {
        $admin_id = auth()->guard('admin')->user()->id;

        $orders = Order::where('admin_id', $admin_id)->get();


        if (count($orders) == 0) {

            return ApiResponse::apiSendResponse(
                200,
                'There Is No Order In This Storehouse.',
                'لا يوجد لدى هذا المستوع أي طلبات.'
            );
        }


        return ApiResponse::apiSendResponse(
            200,
            'Orders Has Been Retrieved Successfully',
            'تمت إعادة طلبيات المستودع بنجاح',
            OrderResource::collection($orders)
        );
    }

    public function orderDetails(Request $request)
    {
        $order_id = $request->order_id;
        if(!$order_id) {
            return ApiResponse::apiSendResponse(
                400,
                'Some Order Data Are Missed.',
                'بيانات الطلب الذي تقوم به غير مكتملة.'
            );
        }


        $order = Order::where('id', $order_id)->first();

        if (!$order) {
            return ApiResponse::apiSendResponse(
                400,
                'Some Order Data Are Missed.',
                'بيانات الطلب الذي تقوم به غير مكتملة.'
            );
        }

        return ApiResponse::apiSendResponse(
            200,
            'Orders Has Been Retrieved Successfully',
            'تمت إعادة الطلب بنجاح',
            new OrderMedicineResource($order)
        );
    }
    
    public function updateStatus(Request $request)
    {
        $order_id = $request->order_id;
        if(!$order_id) {
            return ApiResponse::apiSendResponse(
                400,
                'Some Order Data Are Missed.',
                'بيانات الطلب الذي تقوم به غير مكتملة.'
            );
        }


        $order = Order::where('id', $order_id)->first();

        if (!$order) {
            return ApiResponse::apiSendResponse(
                400,
                'Some Order Data Are Missed.',
                'بيانات الطلب الذي تقوم به غير مكتملة.'
            );
        }

        if($order->status == 'in_preparation') {
            $order->update([
                'status'=>'sent',
            ]);
            return ApiResponse::apiSendResponse(
                200,
                'Order Status Has Been Modified Successfully.',
                'تم تعديل حالة الطلب بنجاح'
            );
        }
        elseif($order->status == 'sent') {
            $order->update([
                'status'=>'delivered',
            ]);
            return ApiResponse::apiSendResponse(
                200,
                'Order Status Has Been Modified Successfully.',
                'تم تعديل حالة الطلب بنجاح'
            );
        }
        else {
            return ApiResponse::apiSendResponse(
                403,
                'Data Can Not Be Modified Anymore.',
                'لا يمكن تعديل البيانات مجددا'
            );
        }
    }

    public function updatePayment(Request $request)
    {
        $order_id = $request->order_id;
        if(!$order_id) {
            return ApiResponse::apiSendResponse(
                400,
                'Some Order Data Are Missed.',
                'بيانات الطلب الذي تقوم به غير مكتملة.'
            );
        }


        $order = Order::where('id', $order_id)->first();

        if (!$order) {
            return ApiResponse::apiSendResponse(
                400,
                'Some Order Data Are Missed.',
                'بيانات الطلب الذي تقوم به غير مكتملة.'
            );
        }

        if(!$order->payed) {
            $order->update([
                'payed'=>true,
            ]);
            return ApiResponse::apiSendResponse(
                200,
                'Order Payment Status Has Been Modified Successfully.',
                'تم تعديل حالة الدفع بنجاح'
            );
        }
        
        else {
            return ApiResponse::apiSendResponse(
                403,
                'Data Can Not Be Modified Anymore.',
                'لا يمكن تعديل البيانات مجددا'
            );
        }
    }

    public function inPreparationCounter() {
        $admin_id = auth()->guard('admin')->user()->id;
        $orders_count = count(Order::where('status', 'in_preparation')->where('admin_id', $admin_id)->get());
        return ApiResponse::apiSendResponse(
            200,
            'Orders In Preparation Number Has Been Retrieved Successfully!',
            'تم إعادة عدد الطلبات قيد التحضير بنجاح',
            ['Status' => 'In Preaparation', 'Count' => $orders_count]
        );
    }
}
