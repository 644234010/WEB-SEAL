<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\ProductShoes;

class ProductShoeController extends Controller
{


    public function products()
    {
        $products = DB::table('productshoes')
            ->join('categories', 'productshoes.category_id', '=', 'categories.id')
            ->select('productshoes.*', 'categories.categories_name')
            ->orderBy('productshoes.id', 'desc')
            ->paginate(9);

        $recommendedProducts = DB::table('productshoes')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->paginate(3);

        $categories = DB::table('categories')->get(); // ดึงข้อมูลหมวดหมู่จากฐานข้อมูล

        return view('userhome.products', compact('products', 'recommendedProducts', 'categories'));
    }

    public function productsByCategory($categoryId)
    {
        $products = DB::table('productshoes')
            ->join('categories', 'productshoes.category_id', '=', 'categories.id')
            ->select('productshoes.*', 'categories.categories_name')
            ->where('productshoes.category_id', $categoryId)
            ->orderBy('productshoes.id', 'desc')
            ->paginate(9);
    
        $recommendedProducts = DB::table('productshoes')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->paginate(3);
    
        $categories = DB::table('categories')->get();
        $selectedCategory = DB::table('categories')->where('id', $categoryId)->first();
    
        return view('userhome.products_by_category', compact('products', 'recommendedProducts', 'categories', 'selectedCategory'));
    }
    




    public function show($id)
    {
        $product = DB::table('productshoes')
            ->join('categories', 'productshoes.category_id', '=', 'categories.id')
            ->select('productshoes.*', 'categories.categories_name')
            ->where('productshoes.id', $id)
            ->first();
    
        return view('userhome.show', compact('product'));
    }
    
    
    

    public function autocomplete(Request $request)
    {
        $query = $request->input('query');
        $suggestions = DB::table('productshoes')
            ->where('pd_name', 'like', "%{$query}%")
            ->get(['id', 'pd_name']);  // ดึงทั้ง ID และชื่อสินค้า
        
        return response()->json(['suggestions' => $suggestions]);
    }
    
    
}

