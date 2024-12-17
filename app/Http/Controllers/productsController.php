<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\productshoes;

class productsController extends Controller
{
    public function index()
    {
        try {
            $productshoes = DB::table('productshoes')
                ->join('categories', 'productshoes.category_id', '=', 'categories.id')
                ->select('productshoes.*', 'categories.categories_name as categories_name')
                ->orderBy('productshoes.id', 'desc')
                ->paginate(3);

            $categories = DB::table('categories')->get();
    
            return view('myproduct.home', compact('productshoes', 'categories'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    public function create()
    {
        return view('myproduct.from');
    }

    public function insert(Request $request)
    {
        
        $request->validate([
            'pd_name' => 'required|max:50',
            'pd_detail' => 'required|max:1000',
            'pd_price' => 'required|numeric',
            'pd_stock' => 'required|numeric',
            'category_id' => 'required|max:100',
            'pd_color' => 'required|max:100',
            'pd_image' => 'image|mimes:jpeg,jpg,gif,svg|max:3048',
            
        ], 
        [
            'pd_name.required' => 'กรุณากรอกชื่อสินค้า',
            'pd_name.max' => 'กรุณากรอกชื่อรถไม่เกิน 50 ตัวอักษร',
            'pd_detail.required' => 'กรุณากรอกรายละเอียดสินค้า',
            'pd_detail.max' => 'กรุณากรอกรายละเอียดไม่เกิน 1000 ตัวอักษร',
            'pd_price.required' => 'กรุณากรอกราคา',
            'pd_price.numeric' => 'กรุณากรอกตัวเลขเท่านั้นสำหรับราคา',
            'pd_stock.numeric' => 'กรุณากรอกตัวเลขเท่านั้นสำหรับจำนวน',
            'category_id.required' => 'กรุณากรอกชื่อสินค้า',
            'category_id.max' => 'กรุณากรอกชื่อสินค้าไม่เกิน 100 ตัวอักษร',
            'pd_color.required' => 'กรุณากรอกรายละเอียดสี',
            'pd_color.max' => 'กรุณากรอกรายละเอียดสีไม่เกิน 100 ตัวอักษร',
            'pd_stock.required' => 'กรุณากรอกจำนวนสินค้า',
            'pd_image.required' => 'กรุณาอัปโหลดรูปภาพ',
            'pd_image.max' => 'ขนาดรูปภาพต้องไม่เกิน 3048KB',
            'pd_image.image' => 'รูปภาพต้องเป็นไฟล์ประเภท jpeg,  jpg, gif หรือ svg',
            'pd_image.mimes' => 'รูปภาพต้องเป็นไฟล์ประเภท jpeg,  jpg, gif หรือ svg',

        ]);

        try {        
            if ($request->hasFile('pd_image')) {
                $imagePath = $request->file('pd_image')->store('pd_images', 'public');
            } else {
                $imagePath = null;
            }
           
            $data = [
                'pd_name' => $request->pd_name,
                'pd_detail' => $request->pd_detail,
                'pd_price' => $request->pd_price,
                'category_id' => $request->category_id,
                'pd_color' => $request->pd_color,
                'pd_stock' => $request->pd_stock,
                'pd_image' => $imagePath,

            ];

            $stproduct = DB::table('productshoes')->insert($data);
            if ($stproduct) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data saved successfully'
                ]);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function delete($id)
    {
        try {
            DB::table('productshoes')->where('id', $id)->delete();
            return redirect()->route('myproduct.home')->with('success', 'Product deleted successfully');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    

    public function edit($id)
    {
        try {
            $product = DB::table('productshoes')->where('id', $id)->first();
            $categories = DB::table('categories')->select('id', 'categories_name')->get();
            
            
        
            return view('myproduct.edit', compact('product', 'categories'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    

    public function update(Request $request, $id)
    {
        $request->validate([
            'pd_name' => 'required|max:50',
            'pd_detail' => 'required|max:1000',
            'pd_price' => 'required|numeric',
            'pd_stock' => 'required|numeric',
            'category_id' => 'required|max:100',
            'pd_color' => 'required|max:100',
            'pd_image' => 'image|mimes:jpeg,jpg,gif,svg|max:3048',
            'pd_image_1' => 'nullable|image|mimes:jpeg,jpg,gif,svg|max:2048',
            'pd_image_2' => 'nullable|image|mimes:jpeg,jpg,gif,svg|max:2048',
            'pd_image_3' => 'nullable|image|mimes:jpeg,jpg,gif,svg|max:2048'
        ],
        [
            'pd_name.required' => 'กรุณากรอกชื่อสินค้า',
            'pd_name.max' => 'กรุณากรอกชื่อรถไม่เกิน 50 ตัวอักษร',
            'pd_detail.required' => 'กรุณากรอกรายละเอียดสินค้า',
            'pd_detail.max' => 'กรุณากรอกรายละเอียดไม่เกิน 1000 ตัวอักษร',
            'pd_price.required' => 'กรุณากรอกราคา',
            'pd_price.numeric' => 'กรุณากรอกตัวเลขเท่านั้นสำหรับราคา',
            'pd_stock.numeric' => 'กรุณากรอกตัวเลขเท่านั้นสำหรับจำนวน',
            'category_id.required' => 'กรุณากรอกชื่อสินค้า',
            'category_id.max' => 'กรุณากรอกชื่อสินค้าไม่เกิน 100 ตัวอักษร',
            'pd_color.required' => 'กรุณากรอกรายละเอียดสี',
            'pd_color.max' => 'กรุณากรอกรายละเอียดสีไม่เกิน 100 ตัวอักษร',
            'pd_stock.required' => 'กรุณากรอกจำนวนสินค้า',
            'pd_image.image' => 'รูปภาพต้องเป็นไฟล์ประเภท jpeg,  jpg, gif หรือ svg',
            'pd_image.mimes' => 'รูปภาพต้องเป็นไฟล์ประเภท jpeg,  jpg, gif หรือ svg',
            'pd_image.max' => 'ขนาดรูปภาพต้องไม่เกิน 3048KB',
            'pd_image_1.image' => 'รูปภาพต้องเป็นไฟล์ประเภท jpeg,  jpg, gif หรือ svg',
            'pd_image_1.mimes' => 'รูปภาพต้องเป็นไฟล์ประเภท jpeg,  jpg, gif หรือ svg',
            'pd_image_1.max' => 'ขนาดรูปภาพต้องไม่เกิน 2048KB',
            'pd_image_2.image' => 'รูปภาพต้องเป็นไฟล์ประเภท jpeg,  jpg, gif หรือ svg',
            'pd_image_2.mimes' => 'รูปภาพต้องเป็นไฟล์ประเภท jpeg,  jpg, gif หรือ svg',
            'pd_image_2.max' => 'ขนาดรูปภาพต้องไม่เกิน 2048KB',
            'pd_image_3.image' => 'รูปภาพต้องเป็นไฟล์ประเภท jpeg,  jpg, gif หรือ svg',
            'pd_image_3.mimes' => 'รูปภาพต้องเป็นไฟล์ประเภท jpeg,  jpg, gif หรือ svg',
            'pd_image_3.max' => 'ขนาดรูปภาพต้องไม่เกิน 2048KB'
            
        ]);
    
        try {
            // ดึงข้อมูลสินค้าปัจจุบันจากฐานข้อมูล
            $product = DB::table('productshoes')->where('id', $id)->first();
    
            // ตรวจสอบและบันทึกไฟล์ใหม่ที่อัปโหลด หากมี
            $data = [
                'pd_name' => $request->pd_name,
                'pd_detail' => $request->pd_detail,
                'pd_price' => $request->pd_price,
                'category_id' => $request->category_id,
                'pd_color' => $request->pd_color,
                'pd_stock' => $request->pd_stock,
                'pd_image' => $request->hasFile('pd_image') ? $request->file('pd_image')->store('pd_images', 'public') : $product->pd_image,
                'pd_image_1' => $request->hasFile('pd_image_1') ? $request->file('pd_image_1')->store('pd_image_1', 'public') : $product->pd_image_1,
                'pd_image_2' => $request->hasFile('pd_image_2') ? $request->file('pd_image_2')->store('pd_image_2', 'public') : $product->pd_image_2,
                'pd_image_3' => $request->hasFile('pd_image_3') ? $request->file('pd_image_3')->store('pd_image_3', 'public') : $product->pd_image_3,
            ];
    
            // อัปเดตข้อมูลในฐานข้อมูล
            DB::table('productshoes')->where('id', $id)->update($data);
            return redirect()->route('myproduct.home')->with('success', 'Product updated successfully');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    


    public function search(Request $request)
    {
        $query = $request->input('query', '');
        $categoryId = $request->input('category_id', 'all');
        $page = $request->input('page', 1);

        try {
            $productsQuery = DB::table('productshoes')
                ->join('categories', 'productshoes.category_id', '=', 'categories.id')
                ->select('productshoes.*', 'categories.categories_name');

            if (!empty($query)) {
                $productsQuery->where(function($q) use ($query) {
                    $q->where('productshoes.pd_name', 'like', "%{$query}%")
                      ->orWhere('productshoes.pd_detail', 'like', "%{$query}%");
                });
            }

            if ($categoryId !== 'all') {
                $productsQuery->where('productshoes.category_id', $categoryId);
            }

            $productshoes = $productsQuery->orderBy('productshoes.id', 'desc')
                ->paginate(3);

            $productshoes->appends(['query' => $query, 'category_id' => $categoryId]);

            if ($request->ajax()) {
                return view('myproduct.products_list', compact('productshoes'))->render();
            }

            $categories = DB::table('categories')->select('id', 'categories_name')->get();
            return view('myproduct.home', compact('productshoes', 'categories', 'query', 'categoryId'));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    public function filterByCategory(Request $request, $categoryId)
    {
        try {
            $query = DB::table('productshoes')
                ->join('categories', 'productshoes.category_id', '=', 'categories.id')
                ->select('productshoes.*', 'categories.categories_name');

            if ($categoryId !== 'all') {
                $query->where('productshoes.category_id', $categoryId);
            }

            $productshoes = $query->orderBy('productshoes.id', 'desc')
                ->paginate(3);

            $productshoes->appends(['category_id' => $categoryId]);

            if ($request->ajax()) {
                return view('myproduct.products_list', compact('productshoes'))->render();
            }

            $categories = DB::table('categories')->select('id', 'categories_name')->get();
            return view('myproduct.home', compact('productshoes', 'categories'));
        } catch (\Exception $e) {
            \Log::error('Error in filterByCategory: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}