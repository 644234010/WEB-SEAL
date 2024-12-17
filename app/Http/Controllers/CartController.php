<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\productshoes;

class CartController extends Controller
{

    public function count()
    {
        $cart = session()->get('cart', []);
        $count = array_sum($cart);

        return response()->json(['count' => $count]);
    }

    public function index()
    {
        $cartItems = session()->get('cart', []);
        $productIds = array_keys($cartItems);
        $products = productshoes::whereIn('id', $productIds)->get();

        $total = 0;
        foreach ($products as $product) {
            $quantity = $cartItems[$product->id];
            $total += $product->pd_price * $quantity;
        }

        return view('cart.index', compact('products', 'cartItems', 'total'));
    }

    public function add(Request $request, $id)
    {
        $product = productshoes::findOrFail($id);
        $quantity = $request->input('quantity', 1);
    
        $cart = session()->get('cart', []);
    
        if (isset($cart[$id])) {
            $cart[$id] += $quantity;
        } else {
            $cart[$id] = $quantity;
        }
    
        session()->put('cart', $cart);
    
       
        $updatedCart = session()->get('cart', []);
        
    
        return redirect()->route('cart.index')->with('success', 'สินค้าได้ถูกเพิ่มลงในรถเข็น');
    }
    

    public function remove($id)
    {
        $cart = session()->get('cart', []);
    
        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
    
        $total = $this->calculateTotal();
    
        return response()->json([
            'success' => true,
            'total' => $total
        ]);
    }
    

    public function updateQuantity(Request $request, $id)
    {
        $cart = session()->get('cart', []);
        $quantity = $request->input('quantity');
    
        if (isset($cart[$id])) {
            $cart[$id] = $quantity;
            session()->put('cart', $cart);
        }
    
        $product = productshoes::findOrFail($id);
        $subtotal = $product->pd_price * $quantity;
        $total = $this->calculateTotal();
    
        return response()->json([
            'success' => true,
            'subtotal' => $subtotal, // Returning as float
            'total' => $total // Returning as float
        ]);
    }
    
    
    

    private function calculateTotal()
    {
        $cart = session()->get('cart', []);
        $total = 0;

        foreach ($cart as $id => $quantity) {
            $product = productshoes::find($id);
            if ($product) {
                $total += $product->pd_price * $quantity;
            }
        }

        return $total;
    }


}