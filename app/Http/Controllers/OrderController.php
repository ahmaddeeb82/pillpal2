<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\Medicine;
use App\Models\MedicineOrder;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function createOrder(Request $request) {
    $order_meds = $request->all();
    if (!$order_meds) {
        return ApiResponse::apiSendResponse(400, 'Some Order Details Are Missed.');
    }

    $order = Order::create([
        'user_id' => auth()->user()->id,
        'order_date' => Carbon::now()->toDateString(),
    ]);

    $total_price = 0;

    foreach ($order_meds as $order_med) {
        $medicine = Medicine::where('id', $order_med['medicine_id'])->first();

        if (!$medicine) {
            return ApiResponse::apiSendResponse(400, 'Medicine with ID ' . $order_med['medicine_id'] . ' does not exist.');
        }

        $medicine_price = $medicine->price;
        $quantity_price = $order_med['quantity'] * $medicine_price;
        MedicineOrder::create([
            'order_id' => $order->id,
            'medicine_id' => $order_med['medicine_id'],
            'quantity' => $order_med['quantity'],
            'quantity_price' => $quantity_price,
        ]);
        $total_price += $quantity_price;
    }

    $order->update([
        'total_price' => $total_price,
    ]);

    return ApiResponse::apiSendResponse(200, '', $order);
}

public function userOrders(Request $request) {
    $user_id = auth()->user()->id;
    $orders = Order::where('user_id', $user_id)->get();
    return $orders;
}

public function orderDetails(Request $request) {
    $order = Order::where('id', $request->order_id)->first();
    if (!$order) {
        return ApiResponse::apiSendResponse(400, 'Medicine with ID  does not exist.');
    }
    $medicines = $order->medicines;
    return response()->json($medicines);
}

}