<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReportRequest;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function viewReport(ReportRequest $request) {
        $user = auth()->guard('admin')->user();

        $startTime = $request->start_date.' 00:00:00';
        $endTime = $request->end_date. ' 23:59:59';

        $report['customers count'] = count(Order::where('admin_id', $user->id)
            ->whereNotNull('admin_id')
            ->whereBetween('created_at', [$startTime, $endTime])
            ->select('orders.user_id', DB::raw('count(user_id) AS items_count'))
            ->groupBy('orders.user_id') 
            ->get());

        $report['orders count'] = count(Order::where('admin_id', $user->id)
        ->whereBetween('created_at', [$startTime, $endTime])
        ->get());

        $report['total order price'] = DB::table('orders')
            ->where('admin_id', $user->id)
            ->whereBetween('created_at', [$startTime, $endTime])
            ->sum('total_price');

        $totalCount = DB::select("
        SELECT SUM(medicine_order.quantity) as total_medicines
        FROM orders
        INNER JOIN medicine_order ON orders.id = medicine_order.order_id
        WHERE orders.admin_id = :adminId
        AND orders.created_at BETWEEN :startTime AND :endTime",
        ['adminId' => $user->id, 'startTime' => $startTime, 'endTime' => $endTime])[0]->total_medicines;

        $report['total medicine count'] = $totalCount?intval($totalCount):0;

        $mostSoldMedicine = DB::table('medicine_order')
        ->join('orders', 'medicine_order.order_id', '=', 'orders.id')
        ->where('orders.admin_id', $user->id)
        ->whereBetween('orders.created_at', [$startTime, $endTime])
        ->select('medicine_order.medicine_id', DB::raw('SUM(medicine_order.quantity) as total_quantity'))
        ->groupBy('medicine_order.medicine_id')
        ->orderBy('total_quantity', 'desc')
        ->first();

        $report['most sold medicine id'] = $mostSoldMedicine?$mostSoldMedicine->medicine_id:0;

        return $report;
        
    }
}
