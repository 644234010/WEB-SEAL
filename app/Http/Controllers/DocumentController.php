<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Carbon\Carbon;

class DocumentController extends Controller
{
    public function doc()
    {
        $orders = DB::table('orders')
            ->join('users', 'orders.customer_id', '=', 'users.id')
            ->join('order__items', 'orders.id', '=', 'order__items.order_id')
            ->join('productshoes', 'order__items.product_id', '=', 'productshoes.id')
            ->join('categories', 'productshoes.category_id', '=', 'categories.id')
            ->select(
                'orders.id',
                'orders.created_at',
                'orders.total_amount',
                'orders.status',
                'orders.payment_status',
                'orders.shipping_address',
                'users.name as company',
                'users.email',
                'users.phone_number',
                'categories.categories_name as category_name'
            )
            ->where('orders.status', 'Shipped')
            ->orderBy('orders.created_at', 'desc')
            ->get();

        $totalDocuments = $orders->count();
        $totalAmount = $orders->sum('total_amount');

        $categories = DB::table('categories')->get();

        $brandOrders = DB::table('categories')
            ->join('productshoes', 'categories.id', '=', 'productshoes.category_id')
            ->join('order__items', 'productshoes.id', '=', 'order__items.product_id')
            ->join('orders', 'order__items.order_id', '=', 'orders.id')
            ->select('categories.categories_name', DB::raw('SUM(order__items.quantity) as total_quantity'))
            ->where('orders.status', 'Shipped')  
            ->groupBy('categories.categories_name')
            ->get();

        return view('report.doc', compact('orders', 'totalDocuments', 'totalAmount', 'categories', 'brandOrders'));
    }

    // เพิ่มเมธอดนี้สำหรับการกรองข้อมูลแบบ AJAX
    public function filterOrders(Request $request)
    {
        $category = $request->input('category');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $searchName = $request->input('searchName');

        $query = DB::table('orders')
            ->join('users', 'orders.customer_id', '=', 'users.id')
            ->join('order__items', 'orders.id', '=', 'order__items.order_id')
            ->join('productshoes', 'order__items.product_id', '=', 'productshoes.id')
            ->join('categories', 'productshoes.category_id', '=', 'categories.id')
            ->select(
                'orders.id',
                'orders.created_at',
                'orders.total_amount',
                'orders.status',
                'users.name as company',
                'users.email',
                'categories.categories_name as category_name'
            )
            ->where('orders.status', 'Shipped');

        if ($category) {
            $query->where('categories.id', $category);
        }

        if ($startDate) {
            $query->whereDate('orders.created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('orders.created_at', '<=', $endDate);
        }

        if ($searchName) {
            $query->where('users.name', 'like', '%' . $searchName . '%');
        }

        $filteredOrders = $query->get();

        $totalDocuments = $filteredOrders->count();
        $totalAmount = $filteredOrders->sum('total_amount');

        $brandOrders = DB::table('categories')
            ->join('productshoes', 'categories.id', '=', 'productshoes.category_id')
            ->join('order__items', 'productshoes.id', '=', 'order__items.product_id')
            ->join('orders', 'order__items.order_id', '=', 'orders.id')
            ->select('categories.categories_name', DB::raw('SUM(order__items.quantity) as total_quantity'))
            ->where('orders.status', 'Shipped')  
            ->groupBy('categories.categories_name');

        if ($category) {
            $brandOrders->where('categories.id', $category);
        }

        if ($startDate) {
            $brandOrders->whereDate('orders.created_at', '>=', $startDate);
        }

        if ($endDate) {
            $brandOrders->whereDate('orders.created_at', '<=', $endDate);
        }

        $brandOrdersData = $brandOrders->get();

        return response()->json([
            'orders' => $filteredOrders,
            'totalDocuments' => $totalDocuments,
            'totalAmount' => $totalAmount,
            'brandOrders' => $brandOrdersData
        ]);
    }
    
    public function getSummaryByDate(Request $request)
    {
        $date = $request->input('date');
        
        // ค้นหาเอกสารตามวันที่ที่ระบุ
        $orders = Order::whereDate('created_at', $date)->get();
    
        // สรุปข้อมูล
        $totalDocuments = $orders->count();
        $totalAmount = $orders->sum('total_amount');
    
        return response()->json([
            'totalDocuments' => $totalDocuments,
            'totalAmount' => number_format($totalAmount, 2),
        ]);
    }
    

    public function downloadSelected(Request $request)
    {
        $selectedOrders = json_decode($request->input('selected_orders', '[]'));
    
        if (empty($selectedOrders)) {
            return response()->json(['error' => 'ไม่ได้เลือกรายการใดๆ'], 400);
        }
    
        $orders = DB::table('orders')
            ->join('users', 'orders.customer_id', '=', 'users.id')
            ->select(
                'orders.id',
                'orders.created_at',
                'orders.total_amount',
                'users.name as company',
                'users.email',
                'orders.shipping_address',
                'orders.status',
                'users.phone_number'
            )
            ->whereIn('orders.id', $selectedOrders)
            ->get();
            
        $format = $request->input('format', 'pdf');
    
        if ($format === 'pdf') {
            return $this->generatePDF($orders);
        } elseif ($format === 'excel') {
            return $this->generateExcel($orders);
        }
    
        return response()->json(['error' => 'รูปแบบไฟล์ไม่ถูกต้อง'], 400);
    }
    
    private function generatePDF($orders)
    {
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];
    
        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];
    
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'fontDir' => array_merge($fontDirs, [
                public_path('fonts'),
            ]),
            'fontdata' => $fontData + [
                'sarabun' => [
                    'R' => 'THSarabunNew.ttf',
                    'B' => 'THSarabunNew Bold.ttf',
                    'I' => 'THSarabunNew Italic.ttf',
                    'BI' => 'THSarabunNew BoldItalic.ttf'
                ]
            ],
            'default_font' => 'sarabun'
        ]);
    
        $coverPage = '<div style="text-align: center; font-size: 36px; padding-top: 250px;">
                        รายงานสรุปยอดขาย
                      </div>';
    
        $mpdf->WriteHTML($coverPage);
    
        $orderDetails = [];
        foreach ($orders as $order) {
            $items = DB::table('order__items')
                ->join('productshoes', 'order__items.product_id', '=', 'productshoes.id')
                ->join('categories', 'productshoes.category_id', '=', 'categories.id')
                ->where('order__items.order_id', $order->id)
                ->select(
                    'productshoes.pd_name', 
                    'order__items.quantity', 
                    'order__items.price',  
                    'categories.categories_name as brand'
                )
                ->get();
    
            $paymentDate = DB::table('payments')
                ->where('order_id', $order->id)
                ->value('payment_date');
    
            $orderDetails[$order->id] = [
                'items' => $items,
                'payment_date' => $paymentDate
            ];
        }
    
        $html = view('pdf.reportoder', compact('orders', 'orderDetails'))->render();
        $mpdf->WriteHTML($html);
        $filename = 'orders_' . date('Y-m-d') . '.pdf';
    
        return response($mpdf->Output($filename, 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
    

    private function generateExcel($orders)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
    
        // Set the header cells
        $sheet->setCellValue('A1', 'Order ID');
        $sheet->setCellValue('B1', 'Date');
        $sheet->setCellValue('C1', 'Company');
        $sheet->setCellValue('D1', 'Email');
        $sheet->setCellValue('E1', 'Phone Number');
        $sheet->setCellValue('F1', 'Shipping Address');
        $sheet->setCellValue('G1', 'Total Amount');
        $sheet->setCellValue('H1', 'Shipping Status');
        $sheet->setCellValue('I1', 'Product Name');
        $sheet->setCellValue('J1', 'Brand');
        $sheet->setCellValue('K1', 'Quantity');
        $sheet->setCellValue('L1', 'Price');
    
        $row = 2;
        foreach ($orders as $order) {
            $items = DB::table('order__items')
                ->join('productshoes', 'order__items.product_id', '=', 'productshoes.id')
                ->join('categories', 'productshoes.category_id', '=', 'categories.id')
                ->where('order__items.order_id', $order->id)
                ->select('productshoes.pd_name', 'categories.categories_name as brand', 'order__items.quantity', 'order__items.price')
                ->get();
    
            foreach ($items as $item) {
                $sheet->setCellValue('A' . $row, $order->id);
                $sheet->setCellValue('B' . $row, $order->created_at);
                $sheet->setCellValue('C' . $row, $order->company);
                $sheet->setCellValue('D' . $row, $order->email);
                $sheet->setCellValue('E' . $row, $order->phone_number);
                $sheet->setCellValue('F' . $row, $order->shipping_address);
                $sheet->setCellValue('G' . $row, $order->total_amount);
                $sheet->setCellValue('H' . $row, $order->status);
                $sheet->setCellValue('I' . $row, $item->pd_name);  // Corrected cell index
                $sheet->setCellValue('J' . $row, $item->brand);
                $sheet->setCellValue('K' . $row, $item->quantity);
                $sheet->setCellValue('L' . $row, $item->price);
                $row++;
            }
        }
    
        $writer = new Xlsx($spreadsheet);
        $filename = 'orders_' . date('Y-m-d') . '.xlsx';
    
        ob_start();
        $writer->save('php://output');
        $content = ob_get_contents();
        ob_end_clean();
    
        return response($content, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }    
}
