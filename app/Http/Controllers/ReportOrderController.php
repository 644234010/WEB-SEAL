<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReportOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('orders')
            ->join('users', 'orders.customer_id', '=', 'users.id')
            ->leftJoin('payments', 'orders.id', '=', 'payments.order_id')
            ->select(
                'orders.id',
                'orders.customer_id',
                'orders.total_amount',
                'orders.status',
                'orders.created_at',
                'orders.updated_at',
                'users.name as customer_name',
                'payments.payment_status',
                'payments.payment_date'
            );
    
        if ($request->has('search')) {
            $query->where('users.name', 'like', '%' . $request->search . '%');
        }
    
        if ($request->has('status') && $request->status != 'all') {
            $query->where('orders.status', $request->status);
        }
    
        if ($request->has('payment_status') && $request->payment_status != 'all') {
            $query->where('payments.payment_status', $request->payment_status);
        }
    
        $orders = $query->orderBy('orders.created_at', 'desc')->paginate(10);
    
        if ($request->ajax()) {
            return response()->json([
                'html' => view('report.order_table', compact('orders'))->render(),
                'pagination' => $orders->links()->toHtml()
            ]);
        }
    
        return view('report.historyadmin', compact('orders'));
    }

    public function show($orderId)
    {
        $order = DB::table('orders')
            ->where('orders.id', $orderId)
            ->join('users', 'orders.customer_id', '=', 'users.id')
            ->leftJoin('payments', 'orders.id', '=', 'payments.order_id') 
            ->select(
                'orders.id',
                'orders.customer_id',
                'orders.total_amount',
                'orders.status',
                'orders.payment_status',
                'orders.created_at',
                'orders.updated_at',
                'users.name as customer_name',
                'payments.payment_date'
            )
            ->first();
    
        $orderItems = DB::table('order__items')
            ->join('productshoes', 'order__items.product_id', '=', 'productshoes.id')
            ->where('order_id', $orderId)
            ->select('order__items.*', 'productshoes.pd_name', 'productshoes.pd_image')
            ->get();
    
        $payment = DB::table('payments')->where('order_id', $orderId)->first();
    
        return view('report.order-details', compact('order', 'orderItems', 'payment'));
    }

    public function update(Request $request, $orderId)
    {
        $order = DB::table('orders')->where('id', $orderId)->first();
        if (!$order) {
            return response()->json(['success' => false, 'message' => 'ไม่พบคำสั่งซื้อ'], 404);
        }
    
        $payment = DB::table('payments')->where('order_id', $orderId)->first();
    
        if ($request->has('payment_status')) {
            DB::table('payments')
                ->updateOrInsert(
                    ['order_id' => $orderId],
                    ['payment_status' => $request->input('payment_status')]
                );
        }
    
        if ($request->has('status')) {
            if (!$payment || $payment->payment_status != 'Paid') {
                return response()->json(['success' => false, 'message' => 'ไม่สามารถเปลี่ยนสถานะการจัดส่งได้เนื่องจากยังไม่ได้ชำระเงิน'], 400);
            }
            DB::table('orders')
                ->where('id', $orderId)
                ->update(['status' => $request->input('status')]);
        }
    
        return response()->json(['success' => true, 'message' => 'อัพเดทคำสั่งซื้อเรียบร้อยแล้ว']);
    }

    public function reportall()
    {
        $user = Auth::user();
        $orders = DB::table('orders')
            ->where('customer_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('order.history', compact('orders'));
    }

    public function unpaidOrders()
    {
        $defaultStatus = 'ไม่มีข้อมูลการชำระเงิน';
    
        $unpaidOrders = DB::table('orders')
            ->orderBy('orders.created_at', 'desc')
            ->join('users', 'orders.customer_id', '=', 'users.id')
            ->leftJoin('payments', 'orders.id', '=', 'payments.order_id')
            ->select(
                'orders.id',
                'users.name as customer_name',
                'orders.created_at',
                'orders.total_amount',
                DB::raw('COALESCE(payments.payment_status, ?) as payment_status')
            )
            ->whereNull('payments.payment_status')
            ->orWhere('payments.payment_status', '!=', 'Paid')
            ->setBindings([$defaultStatus, 'Paid'])
            ->get();
    
        return view('report.unpaid-orders', compact('unpaidOrders'));
    }

    public function unpaidOrdersdetail($orderId)
    {
        $order = DB::table('orders')
        ->where('orders.id', $orderId)
        ->join('users', 'orders.customer_id', '=', 'users.id')
        ->select(
            'orders.id',
            'orders.customer_id',
            'orders.total_amount',
            'orders.status',
            'orders.payment_status',
            'orders.created_at',
            'orders.updated_at',
            'users.name as customer_name'
        )
        ->first();

    $orderItems = DB::table('order__items')
        ->join('productshoes', 'order__items.product_id', '=', 'productshoes.id')
        ->where('order_id', $orderId)
        ->select('order__items.*', 'productshoes.pd_name', 'productshoes.pd_image')
        ->get();

    $payment = DB::table('payments')->where('order_id', $orderId)->first();

    return view('report.unpaid-ordersdetail', compact('order', 'orderItems', 'payment'));
    }
    
    public function pendingPaymentOrders()
    {
        $pendingOrders = DB::table('orders')
            ->join('users', 'orders.customer_id', '=', 'users.id')
            ->select(
                'orders.id',
                'users.name as customer_name',
                'orders.created_at',
                'orders.total_amount',
                'orders.status',
                'orders.payment_status'
            )
            ->whereIn('orders.status', ['Pending', 'Processing'])
            ->orderBy('orders.created_at', 'desc')
            ->get();
    
        return view('report.pendingPaymentOrders', compact('pendingOrders'));
    }

    public function pendingPaymentOrdersdetail($orderId)
    {
        $order = DB::table('orders')
        ->where('orders.id', $orderId)
        ->join('users', 'orders.customer_id', '=', 'users.id')
        ->leftJoin('payments', 'orders.id', '=', 'payments.order_id') 
        ->select(
            'orders.id',
            'orders.customer_id',
            'orders.total_amount',
            'orders.status',
            'orders.payment_status',
            'orders.created_at',
            'orders.updated_at',
            'users.name as customer_name',
            'payments.payment_date'
        )
        ->first();

    $orderItems = DB::table('order__items')
        ->join('productshoes', 'order__items.product_id', '=', 'productshoes.id')
        ->where('order_id', $orderId)
        ->select('order__items.*', 'productshoes.pd_name', 'productshoes.pd_image')
        ->get();

    $payment = DB::table('payments')->where('order_id', $orderId)->first();

    return view('report.pendingPaymentOrdersdetail', compact('order', 'orderItems', 'payment'));
    }

    public function brandSalesReport()
    {
        // ดึงข้อมูลแบรนด์และยอดขาย
        $brandOrders = DB::table('categories')
            ->join('productshoes', 'categories.id', '=', 'productshoes.category_id')
            ->join('order__items', 'productshoes.id', '=', 'order__items.product_id')
            ->join('orders', 'order__items.order_id', '=', 'orders.id')
            ->select('categories.categories_name', DB::raw('SUM(order__items.quantity) as total_quantity'))
            ->groupBy('categories.categories_name')
            ->get();

      
        return view('report.Best_selling', compact('brandOrders'));
    }
    
}