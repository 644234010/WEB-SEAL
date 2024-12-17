<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use thiagoalessio\TesseractOCR\TesseractOCR;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function create()
    {
        $cartItems = session('cart', []);
        $products = DB::table('productshoes')->whereIn('id', array_keys($cartItems))->get();
        
        $finalTotal = 0;
        foreach ($products as $product) {
            $finalTotal += $product->pd_price * $cartItems[$product->id];
        }
        $finalTotal += 38; // เพิ่มค่าจัดส่ง
        
        $user = auth()->user();
        $addresses = [
            'address' => $user->address,
            'address1' => $user->address1,
            'address2' => $user->address2,
            'address3' => $user->address3
        ];
        $addresses = array_filter($addresses); // กรองค่าว่างออก
        
        return view('order.create', compact('products', 'cartItems', 'addresses', 'finalTotal'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
    
        $orderData = [
            'customer_id' => $user->id,
            'order_date' => now(),
            'status' => 'Pending',
            'total_amount' => $request->total_amount,
            'shipping_address' => $request->shipping_address,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    
        $orderId = DB::table('orders')->insertGetId($orderData);
    
        foreach ($request->products as $product) {
            DB::table('order__items')->insert([
                'order_id' => $orderId,
                'product_id' => $product['id'],
                'quantity' => $product['quantity'],
                'price' => $product['price'],
            ]);
        }
    
        $paymentData = [
            'order_id' => $orderId,
            'amount' => $request->total_amount,
            'payment_method' => $request->payment_method,
            'payment_status' => $request->payment_method === 'cash_on_delivery' ? 'Pending' : 'Paid',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    
        if ($request->payment_method === 'qr_payment') {
            $paymentData['payment_date'] = Carbon::parse($request->payment_datetime)->format('Y-m-d H:i:s');
            
            if ($request->hasFile('proof_of_payment')) {
                $path = $request->file('proof_of_payment')->store('payment_proofs', 'public');
                $paymentData['slip_filename'] = $path;
    
                $verificationResult = $this->verifyPaymentSlip($path, $request->total_amount);
                if (!$verificationResult['isValid']) {
                    return response()->json([
                        'success' => false,
                        'message' => 'ข้อมูลในสลิปไม่ถูกต้อง กรุณาตรวจสอบและลองใหม่อีกครั้ง'
                    ]);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'กรุณาอัพโหลดสลิปการโอนเงิน'
                ]);
            }
        } else {
            $paymentData['payment_date'] = now();
        }
    
        DB::table('payments')->insert($paymentData);
    
        session()->forget('cart');
    
        return response()->json([
            'success' => true,
            'redirect' => route('order.success', ['orderId' => $orderId])
        ]);
    }
    
    
    public function success($orderId)
    {
        $order = DB::table('orders')->where('id', $orderId)->first();
        $orderItems = DB::table('order__items')
            ->join('productshoes', 'order__items.product_id', '=', 'productshoes.id')
            ->where('order_id', $orderId)
            ->select('order__items.*', 'productshoes.pd_name', 'productshoes.pd_image')
            ->get();
        
        return view('order.success', compact('order', 'orderItems'));
    }

    private function verifyPaymentSlip($imagePath, $expectedAmount)
    {
        try {
            // อ่านข้อมูลจากสลิปโดยใช้ Tesseract OCR
            $text = (new TesseractOCR(storage_path('app/public/' . $imagePath)))
                ->lang('tha', 'eng')
                ->run();
    
            \Log::info('Extracted Text:', ['text' => $text]);
    
            // ลบช่องว่างและอักขระที่ไม่ใช่ตัวเลขหรือตัวอักษร
            $cleanText = preg_replace('/\W+/', '', $text);
    
            // ข้อมูลที่คาดหวัง
            $expectedAccount = "3125";
            $formattedExpectedAmount = number_format($expectedAmount, 2, '.', ''); // 00.00 format
    
            // การตรวจสอบข้อมูล
            $isAccountMatch = strpos($cleanText, $expectedAccount) !== false;
            $isAmountMatch = strpos($cleanText, str_replace('.', '', $formattedExpectedAmount)) !== false;
    
            // ตรวจสอบว่าแต่ละเงื่อนไขตรงกันหรือไม่
            $isValid = $isAccountMatch && $isAmountMatch;
    
            return [
                'isValid' => $isValid,
                'details' => [
                    'accountMatch' => $isAccountMatch,
                    'amountMatch' => $isAmountMatch,
                    'text' => $cleanText
                ]
            ];
        } catch (\Exception $e) {
            \Log::error('Payment slip verification error: ' . $e->getMessage());
            return ['isValid' => false, 'error' => $e->getMessage()];
        }
    }
}