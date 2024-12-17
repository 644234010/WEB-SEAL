<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderHistoryController extends Controller
{
    public function index()
    {

    $user = Auth::user();


    $orders = DB::table('orders')
        ->where('orders.customer_id', $user->id) 
        ->orderBy('orders.created_at', 'desc')
        ->leftJoin('payments', 'orders.id', '=', 'payments.order_id')
        ->select(
            'orders.*',
            'payments.payment_status'
        )
        ->get();

    return view('order.history', compact('orders'));
    }

    public function show($orderId)
    {
        $order = DB::table('orders')
            ->leftJoin('payments', 'orders.id', '=', 'payments.order_id')
            ->where('orders.id', $orderId)
            ->select(
                'orders.id',
                'orders.customer_id',
                'orders.customer_name',  
                'orders.order_date',
                'orders.status',          
                'orders.total_amount',
                'orders.shipping_address',
                'orders.billing_address',
                'orders.created_at',
                'orders.updated_at',
                'payments.payment_status',
                'payments.payment_date'  
            )
            ->first();
    
        $orderItems = DB::table('order__items')
            ->join('productshoes', 'order__items.product_id', '=', 'productshoes.id')
            ->where('order__items.order_id', $orderId)
            ->select('order__items.*', 'productshoes.pd_name', 'productshoes.pd_image')
            ->get();
    
        return view('order.details', compact('order', 'orderItems'));
    }
    
    
}