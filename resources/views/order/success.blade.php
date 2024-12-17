@extends('layoutsuser.head')

@section('content')
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-light border-bottom-0">
                <h5 class="mb-0">ใบเสร็จการสั่งซื้อ</h5>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <h6 class="border-bottom pb-2">รายละเอียดคำสั่งซื้อ</h6>
                    <p>หมายเลขคำสั่งซื้อ: {{ $order->id }}</p>
                    <p>วันที่: {{ date('d/m/Y', strtotime($order->created_at)) }}</p>
                </div>

                <div class="mb-4">
                    <h6 class="border-bottom pb-2">ที่อยู่จัดส่ง</h6>
                    <p>{{ $order->shipping_address }}</p>
                </div>

                <div class="mb-4">
                    <h6 class="border-bottom pb-2">รายการสินค้า</h6>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>รูป</th>
                                <th>ชื่อสินค้า</th>
                                <th>จำนวน</th>
                                <th>ราคาต่อชิ้น</th>
                                <th>ราคารวม</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orderItems as $item)
                                <tr>
                                    <td><img src="{{ asset('storage/' . $item->pd_image) }}" width="50"></td>
                                    <td>{{ $item->pd_name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>฿{{ number_format($item->price, 2) }}</td>
                                    <td>฿{{ number_format($item->quantity * $item->price, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mb-4">
                    <h6 class="border-bottom pb-2">สรุปยอด</h6>
                    <p>รวมการสั่งซื้อ: ฿{{ number_format($order->total_amount - 38, 2) }}</p>
                    <p>ค่าจัดส่ง: ฿38.00</p>
                    <p>ยอดชำระเงินทั้งหมด: ฿{{ number_format($order->total_amount, 2) }}</p>
                </div>

                <div class="text-center no-print">
                    <button onclick="window.print();" class="btn btn-primary">พิมพ์ใบเสร็จ</button>
                    <a href="{{ route('userhome.products') }}" class="btn btn-secondary">กลับสู่หน้าแรก</a>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            .no-print {
                display: none;
            }
            /* ซ่อน head และ footer เมื่อพิมพ์ */
            header, footer {
                display: none;
            }
            /* ขยายขนาดของใบเสร็จให้เต็มหน้าจอ */
            .container {
                margin: 0;
                padding: 0;
                width: 100%;
            }
        }
    </style>
@endsection
