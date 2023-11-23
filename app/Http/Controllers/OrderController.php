<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Resources\OrderMedicineResource;
use App\Http\Resources\OrderResource;
use App\Models\Medicine;
use App\Models\MedicineOrder;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

use function PHPUnit\Framework\isEmpty;

class OrderController extends Controller
{
    public function createOrder(Request $request)
    {

        $order_meds = $request->all();

        if (!$order_meds) {

            if(LaravelLocalization::getCurrentLocale() == 'ar') {
                $message = 'بيانات الطلب الذي تقوم به غير مكتملة.';
            }
            else {
                $message = 'Some Order Data Are Missed.';
            }

            return ApiResponse::apiSendResponse(400, $message);
        }

        $order = Order::create([
            'user_id' => auth()->user()->id,
            'order_date' => Carbon::now()->toDateString(),
        ]);

        $total_price = 0;
        $error = false;

        foreach ($order_meds as $order_med) {

            if (!isset($order_med['medicine_id']) || !isset($order_med['quantity'])) {
                $error = true;
                break;
            }

            $medicine = Medicine::where('id', $order_med['medicine_id'])->first();

            if (!$medicine) {

                if(LaravelLocalization::getCurrentLocale() == 'ar') {
                    $message = 'الدواء ذو الرقم' .  $order_med['medicine_id'] . 'غير موجود';
                }
                else {
                    $message = 'Medicine with ID ' . $order_med['medicine_id'] . ' does not exist.';
                }

                return ApiResponse::apiSendResponse(400, $message);
            }

            $medicine_price = $medicine->price;

            $quantity_price = $order_med['quantity'] * $medicine_price;

            $addedMedicines[] = MedicineOrder::create([
                'order_id' => $order->id,
                'medicine_id' => $order_med['medicine_id'],
                'quantity' => $order_med['quantity'],
                'quantity_price' => $quantity_price,
            ]);

            $total_price += $quantity_price;
        }

        if ($error) {
            if($addedMedicines) {
                foreach ($addedMedicines as $addedMedicine) {
                    $addedMedicine->delete();
                }
            }

            $order->delete();

            if(LaravelLocalization::getCurrentLocale() == 'ar') {
                $message = 'بيانات الطلب الذي تقوم به غير مكتملة.';
            }
            else {
                $message = 'Some Order Data Are Missed.';
            }

            return ApiResponse::apiSendResponse(400, $message);
        }

        $order->update([
            'total_price' => $total_price,
        ]);

        if(LaravelLocalization::getCurrentLocale() == 'ar') {
            $message = 'تمت إضافة الطلب بنجاح';
        }
        else {
            $message = 'Order Has Been Added Successfully';
        }

        return ApiResponse::apiSendResponse(200, $message, OrderResource::collection(Order::where('user_id', auth()->user()->id)->get()));
    }

    public function userOrders(Request $request)
    {
        $user_id = auth()->user()->id;

        $orders = Order::where('user_id', $user_id)->get();

        if(isEmpty($orders)) {

            if(LaravelLocalization::getCurrentLocale() == 'ar') {
                $message = 'لا يقم هذا المستخدم بإضافة أي طلبيات';
            }
            else {
                $message = 'There Is No Order For This User.';
            }

            return ApiResponse::apiSendResponse(200, $message);
        }

        if(LaravelLocalization::getCurrentLocale() == 'ar') {
            $message = 'تمت إعادة طلبيات المستخدم بنجاح';
        }
        else {
            $message = 'Orders Has Been Retrieved Successfully';
        }

        return ApiResponse::apiSendResponse(200,$message , OrderResource::collection($orders));
    }

    public function orderDetails(Request $request)
    {
        $order = Order::where('id', $request->order_id)->first();

        if (!$order) {

            if(LaravelLocalization::getCurrentLocale() == 'ar') {
                $message = 'بيانات الطلب الذي تقوم به غير مكتملة.';
            }
            else {
                $message = 'Some Order Data Are Missed.';
            }

            return ApiResponse::apiSendResponse(400, $message);
        }

        if(LaravelLocalization::getCurrentLocale() == 'ar') {
            $message = 'تمت إعادة الطلب بنجاح';
        }
        else {
            $message = 'Orders Has Been Retrieved Successfully';
        }

        return ApiResponse::apiSendResponse(200, $message, new OrderMedicineResource($order));
    }
}
