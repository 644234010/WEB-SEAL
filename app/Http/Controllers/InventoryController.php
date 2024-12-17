<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\productshoes;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function index()
    {     
        // จำนวนสินค้าทั้งหมด
        $totalProducts = DB::table('productshoes')->count();

        // จำนวนรวมแต่ละแบรนด์มีสินค้ากี่รายการ
        $categoryProductCounts = DB::table('productshoes')
        ->join('categories', 'productshoes.category_id', '=', 'categories.id')
        ->select('categories.categories_name', DB::raw('COUNT(*) as count'))
        ->groupBy('categories.id', 'categories.categories_name')
        ->get();
    
        // รวม stock สินค้าทั้งหมดมีกี่ชิ้น
        $totalStock = DB::table('productshoes')->sum('pd_stock');
    
        // สินค้าที่กำลังจะหมด stock (เช่น น้อยกว่า 10 ชิ้น)
        $lowStockProducts = DB::table('productshoes')
        ->join('categories', 'productshoes.category_id', '=', 'categories.id')
        ->select('productshoes.*', 'categories.categories_name')
        ->where('pd_stock', '<', 10)
        ->orderBy('pd_stock', 'asc')
        ->get();

        // สินค้าที่ขายได้ในแต่ละแบรนด์
        // $categorySales = DB::table('order__items')
        //         ->join('productshoes', 'order__items.product_id', '=', 'productshoes.id')
        //         ->join('categories', 'productshoes.category_id', '=', 'categories.id')
        //         ->select('categories.categories_name', DB::raw('SUM(order__items.quantity) as total_sold'))
        //         ->groupBy('categories.id', 'categories.categories_name')
        //         ->get();
    
        return view('inventory', compact(
            'totalProducts',
            'categoryProductCounts',
            'totalStock',
            'lowStockProducts',
            //'categorySales'
        ));
    }
    public function indexz()
    {
        try {
            $productshoes = DB::table('productshoes')
                ->join('categories', 'productshoes.category_id', '=', 'categories.id')
                ->select('productshoes.*', 'categories.categories_name as categories_name')
                ->orderBy('productshoes.id', 'desc')
                ->paginate(3);

            $categories = DB::table('categories')->get();
    
            return view('inventory.home', compact('productshoes', 'categories'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    public function create()
    {
        return view('inventory.from');
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
            return redirect()->route('inventory.home')->with('success', 'Product deleted successfully');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    

    public function edit($id)
    {
        try {
            $product = DB::table('productshoes')->where('id', $id)->first();
            $categories = DB::table('categories')->select('id', 'categories_name')->get();
            
            
        
            return view('inventory.edit', compact('product', 'categories'));
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
            return redirect()->route('inventory.home')->with('success', 'Product updated successfully');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    


    public function search(Request $request)
    {
        $query = $request->input('query');
        $categoryId = $request->input('category_id');
        $page = $request->input('page', 1);
    
        try {
            $productshoes = DB::table('productshoes')
                ->join('categories', 'productshoes.category_id', '=', 'categories.id')
                ->select('productshoes.*', 'categories.categories_name')
                ->where(function($q) use ($query) {
                    $q->where('productshoes.pd_name', 'like', "%{$query}%")
                      ->orWhere('productshoes.pd_detail', 'like', "%{$query}%");
                })
                ->when($categoryId && $categoryId != 'all', function($q) use ($categoryId) {
                    return $q->where('productshoes.category_id', $categoryId);
                })
                ->orderBy('productshoes.id', 'desc')
                ->paginate(3);
    
            $productshoes->appends(['query' => $query, 'category_id' => $categoryId]);
    
            if ($request->ajax()) {
                return view('inventory.products_list', compact('productshoes'))->render();
            }
    
            $categories = DB::table('categories')->select('id', 'categories_name')->get();
            return view('inventory.home', compact('productshoes', 'categories', 'query', 'categoryId'));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function filterByCategorys($categoryId)
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
    
            if (request()->ajax()) {
                return view('inventory.products_list', compact('productshoes'))->render();
            }
    
            $categories = DB::table('categories')->select('id', 'categories_name')->get();
            return view('inventory.home', compact('productshoes', 'categories'));
        } catch (\Exception $e) {
            \Log::error('Error in filterByCategorys: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function showinventory()
    {
        $user = Auth::user();
        return view('myprofileinventory.showinventory', compact('user'));
    }

    public function editinventory()
    {
        $user = Auth::user();
        return view('myprofileinventory.editinventory', compact('user'));
    }

    public function updatinventory(Request $request)
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

        return redirect()->route('myprofileinventory.showinventory')->with('success', 'ข้อมูลถูกอัปเดตเรียบร้อยแล้ว');
    }
}
