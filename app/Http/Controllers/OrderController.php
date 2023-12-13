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
use Illuminate\Support\Facades\Session;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

use function PHPUnit\Framework\isEmpty;

class OrderController extends Controller
{
    public function createOrder(Request $request)
    {
        $admin_id = $request->header('Str');
        if(!$admin_id) {
            return ApiResponse::apiSendResponse(
                400,
                'Some Data Are Missed.',
                'بيانات الطلب الذي تقوم به غير مكتملة.'
           );
        }
        $order_meds = $request->all();

        if (!$order_meds) {

            return ApiResponse::apiSendResponse(
                400,
                'Some Order Data Are Missed.',
                'بيانات الطلب الذي تقوم به غير مكتملة.'
            );
        }

        $order = Order::create([
            'user_id' => auth()->user()->id,
            'order_date' => Carbon::now()->toDateString(),
            'admin_id' => $admin_id,
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

                return ApiResponse::apiSendResponse(
                    400,
                    'Medicine with ID ' . $order_med['medicine_id'] . ' does not exist.',
                    'الدواء ذو الرقم' .  $order_med['medicine_id'] . 'غير موجود'
                );
            }

            $default_quantity = $medicine->quantity;

            if($default_quantity < $order_med['quantity']) {
                $error = true;
                $ordered_name = $medicine->commercial_name;
                break;
            }

            $medicine_price = $medicine->price;

            $quantity_price = $order_med['quantity'] * $medicine_price;

            $addedMedicines[] = MedicineOrder::create([
                'order_id' => $order->id,
                'medicine_id' => $order_med['medicine_id'],
                'quantity' => $order_med['quantity'],
                'quantity_price' => $quantity_price,
            ]);

            $medicine->update([
                'quantity' => $default_quantity - $order_med['quantity'],
            ]);

            $total_price += $quantity_price;
        }

        if ($error) {
            if (isset($addedMedicines)) {
                foreach ($addedMedicines as $addedMedicine) {
                    if(isset($ordered_name)) {
                        $reversed_medicine = Medicine::where('id', $addedMedicine->medicine_id)->first();
                        $reversed_medicine->update([
                            'quantity' => $reversed_medicine->quantity + $addedMedicine->quantity,
                        ]);
                    }
                    $addedMedicine->delete();
                }
            }

            $order->delete();
            if(isset($ordered_name)) {
                return ApiResponse::apiSendResponse(
                    200,
                    'The Quantity You Ordered Of The Medicine '. $ordered_name . ' Is Not Existed.',
                    'الكمية التي قمت بطلبها من الدواء' . $ordered_name . 'غير موجودة'
                );
            }
            return ApiResponse::apiSendResponse(
                400,
                'Some Order Data Are Missed.',
                'بيانات الطلب الذي تقوم به غير مكتملة.'
            );
        }

        $order->update([
            'total_price' => $total_price,
        ]);

        return ApiResponse::apiSendResponse(
            200,
            'Order Has Been Added Successfully',
            'تمت إضافة الطلب بنجاح',
            OrderResource::collection(Order::where('user_id', auth()->user()->id)->get())
        );
    }

    public function userOrders(Request $request)
    {
        $user_id = auth()->user()->id;

        $orders = Order::where('user_id', $user_id)->get();


        if (count($orders) == 0) {

            return ApiResponse::apiSendResponse(
                200,
                'There Is No Order For This User.',
                'لا يقم هذا المستخدم بإضافة أي طلبيات'
            );
        }


        return ApiResponse::apiSendResponse(
            200,
            'Orders Has Been Retrieved Successfully',
            'تمت إعادة طلبيات المستخدم بنجاح',
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

    public function deleteOrder(Request $request)
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
        if(!$order) {
            return ApiResponse::apiSendResponse(
                400,
                'Some Order Data Are Missed.',
                'بيانات الطلب الذي تقوم به غير مكتملة.'
            );
        }
        
        if($order->status != 'in_preparation') {
            return ApiResponse::apiSendResponse(
                403,
                'Forbiden',
                'هذا الإجراء غير مسموح به'
            );
        }

        $medicines = $order->medicines;

        foreach( $medicines as $medicine ) {
            $medicine->update([
                'quantity' => $medicine->quantity + $medicine->pivot->quantity,
            ]);
        }

        $order->delete();

        $user_id = auth()->user()->id;

        $orders = Order::where('user_id', $user_id)->get();

        return ApiResponse::apiSendResponse(
            200,
            'Orders Has Been Deleted Successfully',
            'تمت حذف طلبيات المستخدم بنجاح',
            OrderResource::collection($orders)
        );
    }
}
