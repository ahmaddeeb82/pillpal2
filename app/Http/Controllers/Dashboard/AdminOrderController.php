<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReportRequest;
use App\Http\Resources\OrderMedicineResource;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function adminInPreparationOrders(Request $request)
    {
        $admin_id = auth()->guard('admin')->user()->id;

        $orders = Order::where('admin_id', $admin_id)->where('status', 'in_preparation')->get();


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

    public function adminSentOrders(Request $request)
    {
        $admin_id = auth()->guard('admin')->user()->id;

        $orders = Order::where('admin_id', $admin_id)->where('status', 'sent')->get();


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

    public function adminDeliveredOrders(Request $request)
    {
        $admin_id = auth()->guard('admin')->user()->id;

        $orders = Order::where('admin_id', $admin_id)->where('status', 'delivered')->get();


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

    public function sentCounter() {
        $admin_id = auth()->guard('admin')->user()->id;
        $orders_count = count(Order::where('status', 'sent')->where('admin_id', $admin_id)->get());
        return ApiResponse::apiSendResponse(
            200,
            'Orders Sent Number Has Been Retrieved Successfully!',
            'تم إعادة عدد الطلبات المرسلة بنجاح',
            ['Status' => 'Sent', 'Count' => $orders_count]
        );
    }

    public function deliveredCounter() {
        $admin_id = auth()->guard('admin')->user()->id;
        $orders_count = count(Order::where('status', 'delivered')->where('admin_id', $admin_id)->get());
        return ApiResponse::apiSendResponse(
            200,
            'Orders Delivered Number Has Been Retrieved Successfully!',
            'تم إعادة عدد الطلبات المستلمة بنجاح',
            ['Status' => 'Delivered', 'Count' => $orders_count]
        );
    }

    public function reportOrders(ReportRequest $request)
    {
        $admin_id = auth()->guard('admin')->user()->id;

        $startTime = $request->start_date.' 00:00:00';
        $endTime = $request->end_date. ' 23:59:59';

        $orders = Order::where('admin_id', $admin_id)->whereBetween('created_at', [$startTime, $endTime])->get();

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
}
