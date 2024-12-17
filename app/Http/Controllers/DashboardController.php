<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {

        // จำนวนคำสั่งซื้อที่ยังไม่ได้ชำระเงิน
        $pendingPaymentCount = DB::table('orders')
            ->leftJoin('payments', 'orders.id', '=', 'payments.order_id')
            ->where(function($query) {
                $query->whereNull('payments.payment_status')
                    ->orWhere('payments.payment_status', 'Pending')
                    ->orWhere('payments.payment_status', '<>', 'Paid');
            })
            ->count();

        // จำนวนคำสั่งซื้อที่ชำระเงินแล้ว
        $paidOrderCount = DB::table('orders')
            ->join('payments', 'orders.id', '=', 'payments.order_id')
            ->where('payments.payment_status', 'Paid')
            ->count();

        // จำนวนสินค้าที่รอจัดส่ง
        $pendingShipmentCount = DB::table('orders')
            ->where('status', 'Processing')
            ->count();

        // ยอดขายรวม
        $totalSales = DB::table('orders')
            ->join('payments', 'orders.id', '=', 'payments.order_id')
            ->where('payments.payment_status', 'Paid')
            ->sum('orders.total_amount');

        // ยอดขายรายวัน
        $dailySales = DB::table('orders')
            ->join('payments', 'orders.id', '=', 'payments.order_id')
            ->where('payments.payment_status', 'Paid')
            ->whereDate('orders.created_at', Carbon::today())
            ->sum('orders.total_amount');

        // ยอดขายรายเดือน
        $monthlySales = DB::table('orders')
            ->join('payments', 'orders.id', '=', 'payments.order_id')
            ->where('payments.payment_status', 'Paid')
            ->whereMonth('orders.created_at', Carbon::now()->month)
            ->sum('orders.total_amount');

        // จำนวนคำสั่งซื้อที่จัดส่งแล้ว
        $shippedOrderCount = DB::table('orders')
            ->where('status', 'Shipped')
            ->count();

        // ยอดขายรายปี
        $yearlySales = DB::table('orders')
            ->join('payments', 'orders.id', '=', 'payments.order_id')
            ->where('payments.payment_status', 'Paid')
            ->whereYear('orders.created_at', Carbon::now()->year)
            ->sum('orders.total_amount');

        // รายการสั่งซื้อล่าสุดที่ยังไม่ได้ชำระเงิน
        $latestUnpaidOrders = DB::table('orders')
            ->leftJoin('payments', 'orders.id', '=', 'payments.order_id')
            ->where(function($query) {
                $query->whereNull('payments.payment_status')
                    ->orWhere('payments.payment_status', 'Pending')
                    ->orWhere('payments.payment_status', '<>', 'Paid');
            })
            ->orderBy('orders.created_at', 'desc')
            ->limit(5)
            ->get();

        // รายการสั่งซื้อล่าสุดที่รอดำเนินการ
        $latestPendingOrders = DB::table('orders')
            ->where('status', 'Pending')
            ->orderBy('orders.created_at', 'desc')
            ->limit(5)
            ->get();

        
        $dailySalesData = DB::table('orders')
            ->join('payments', 'orders.id', '=', 'payments.order_id')
            ->where('payments.payment_status', 'Paid')
            ->whereDate(DB::raw('TRUNC(orders.created_at)'), '>=', Carbon::today()->subDays(30))
            ->select(DB::raw('TRUNC(orders.created_at) as order_date'), DB::raw('SUM(orders.total_amount) as total'))
            ->groupBy(DB::raw('TRUNC(orders.created_at)'))
            ->get();

        $monthlySalesData = DB::table('orders')
            ->join('payments', 'orders.id', '=', 'payments.order_id')
            ->where('payments.payment_status', 'Paid')
            ->whereYear('orders.created_at', Carbon::now()->year)
            ->select(DB::raw('EXTRACT(MONTH FROM orders.created_at) as month'), DB::raw('SUM(orders.total_amount) as total'))
            ->groupBy(DB::raw('EXTRACT(MONTH FROM orders.created_at)'))
            ->get();

        $yearlySalesData = DB::table('orders')
            ->join('payments', 'orders.id', '=', 'payments.order_id')
            ->where('payments.payment_status', 'Paid')
            ->select(DB::raw('EXTRACT(YEAR FROM orders.created_at) as year'), DB::raw('SUM(orders.total_amount) as total'))
            ->groupBy(DB::raw('EXTRACT(YEAR FROM orders.created_at)'))
            ->get();
        
        

        return view('dashboard', compact(
            'pendingPaymentCount',
            'paidOrderCount',
            'pendingShipmentCount',
            'totalSales',
            'dailySales',
            'monthlySales',
            'shippedOrderCount',
            'yearlySales',
            'latestUnpaidOrders',
            'latestPendingOrders',
            'dailySalesData', // เพิ่มข้อมูลยอดขายรายวัน
            'monthlySalesData', // เพิ่มข้อมูลยอดขายรายเดือน
            'yearlySalesData' // เพิ่มข้อมูลยอดขายรายปี
        ));
    }

    

}

