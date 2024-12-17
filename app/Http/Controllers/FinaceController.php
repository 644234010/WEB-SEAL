<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class FinaceController extends Controller
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
        
        

        return view('finace', compact(
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
    
        return view('finacereport.unpaid-orders', compact('unpaidOrders'));
    }

    public function unpaidOrdersdetail($orderId)
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

    return view('finacereport.unpaid-ordersdetail', compact('order', 'orderItems', 'payment'));
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
                    // ถ้าไม่มีข้อมูลการชำระเงิน ให้สร้างใหม่
                    DB::table('payments')->insert([
                        'order_id' => $orderId,
                        'payment_status' => $request->payment_status,
                        'payment_date' => now(),
                    ]);
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

        return view('finacereport.reportall', compact('orders'));
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
    
        return view('finacereport.reportall', compact('orders'));
    }

    public function showfinace()
    {
        $user = Auth::user();
        return view('myprofilefinace.showfinace', compact('user'));
    }

    public function editfinace()
    {
        $user = Auth::user();
        return view('myprofilefinace.editfinace', compact('user'));
    }

    public function updatfinace(Request $request)
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

        return redirect()->route('myprofilefinace.showfinace')->with('success', 'ข้อมูลถูกอัปเดตเรียบร้อยแล้ว');
    }
}
    
