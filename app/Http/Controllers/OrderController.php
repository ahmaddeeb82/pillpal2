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

use function PHPUnit\Framework\isEmpty;

class OrderController extends Controller
{
    public function createOrder(Request $request)
    {

        $order_meds = $request->all();

        if (!$order_meds) {
            return ApiResponse::apiSendResponse(400, 'Some Order Data Are Missed.');
        }

        $order = Order::create([
            'user_id' => auth()->user()->id,
            'order_date' => Carbon::now()->toDateString(),
        ]);

        $total_price = 0;
        $error = false;

        foreach ($order_meds as $order_med) {

            if (!isset($order_med['medicine_id']) || !$order_med['quantity']) {
                $error = true;
                break;
            }

            $medicine = Medicine::where('id', $order_med['medicine_id'])->first();

            if (!$medicine) {
                return ApiResponse::apiSendResponse(400, 'Medicine with ID ' . $order_med['medicine_id'] . ' does not exist.');
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
            foreach ($addedMedicines as $addedMedicine) {
                $addedMedicine->delete();
            }
            return ApiResponse::apiSendResponse(400, 'Some Order Data Are Missed');
        }

        $order->update([
            'total_price' => $total_price,
        ]);

        return ApiResponse::apiSendResponse(200, 'Order Has Been Added Successfully', OrderResource::collection(Order::where('user_id', auth()->user()->id)->get()));
    }

    public function userOrders(Request $request)
    {
        $user_id = auth()->user()->id;

        $orders = Order::where('user_id', $user_id)->get();

        if(isEmpty($orders)) {
            return ApiResponse::apiSendResponse(200, 'There Is No Order For This User.');
        }

        return ApiResponse::apiSendResponse(200, 'Orders Has Been Retrieved Successfully', OrderResource::collection($orders));
    }

    public function orderDetails(Request $request)
    {
        $order = Order::where('id', $request->order_id)->first();

        if (!$order) {
            return ApiResponse::apiSendResponse(400, 'Some Order Deatails Are Missed');
        }

        return ApiResponse::apiSendResponse(200, 'Orders Has Been Retrieved Successfully', new OrderMedicineResource($order));
    }
}
