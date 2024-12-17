<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ShippingController extends Controller
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

        


        $pendingOrderCount = DB::table('orders')
            ->where('status', 'Pending')
            ->count();
        
        
        

        return view('shipping', compact(
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
            'pendingOrderCount',
            'pendingShipmentCount',
            'shippedOrderCount'
        ));
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
    
        return view('Shippingreport.pendingPaymentOrders', compact('pendingOrders'));
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

    return view('Shippingreport.pendingPaymentOrdersdetail', compact('order', 'orderItems', 'payment'));
    }
    
    public function update(Request $request, $orderId)
    {
        try {
            DB::beginTransaction();

            $order = DB::table('orders')->where('id', $orderId)->first();
            if (!$order) {
                throw new \Exception('Order not found');
            }

            if ($request->has('status')) {
                DB::table('orders')
                    ->where('id', $orderId)
                    ->update(['status' => $request->status]);
            }
            
            if ($request->has('payment_status')) {
                $payment = DB::table('payments')->where('order_id', $orderId)->first();
                if ($payment) {
                    DB::table('payments')
                        ->where('order_id', $orderId)
                        ->update(['payment_status' => $request->payment_status]);
                } else {
                    throw new \Exception('Payment not found for this order');
                }
            }
            
            DB::commit();
            
            $updatedOrder = DB::table('orders')->where('id', $orderId)->first();
            $updatedPayment = DB::table('payments')->where('order_id', $orderId)->first();
            
            return response()->json([
                'success' => true,
                'message' => 'Order updated successfully',
                'order' => $updatedOrder,
                'payment' => $updatedPayment
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating order: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'error' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getStatus($orderId)
    {
        try {
            $order = DB::table('orders')->where('id', $orderId)->first();
            if (!$order) {
                throw new \Exception('Order not found');
            }
            
            return response()->json([
                'success' => true,
                'order' => $order
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting order status: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'error' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    public function reportall()
    {
        $user = Auth::user();
        $orders = DB::table('orders')
            ->where('customer_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('Shippingreport.reportall', compact('orders'));
    }

    public function history(Request $request)
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
    
 
    
        $orders = $query->orderBy('orders.created_at', 'desc')->paginate(10);
    
        if ($request->ajax()) {
            return response()->json([
                'html' => view('report.order_table', compact('orders'))->render(),
                'pagination' => $orders->links()->toHtml()
            ]);
        }
    
        return view('Shippingreport.reportall', compact('orders'));
    }

    public function showshiping()
    {
        $user = Auth::user();
        return view('myprofileshiping.showshiping', compact('user'));
    }

    public function editshiping()
    {
        $user = Auth::user();
        return view('myprofileshiping.editshiping', compact('user'));
    }

    public function updatshiping(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:1000',
            'phone_number' => 'required|string|max:11',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->address = $request->address;
        $user->phone_number = $request->phone_number;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('myprofileshiping.showshiping')->with('success', 'ข้อมูลถูกอัปเดตเรียบร้อยแล้ว');
    }
}