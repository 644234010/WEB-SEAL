<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>รายงานคำสั่งซื้อ</title>
    <style>
        body {
            font-family: sarabun, sans-serif;
            font-size: 14px;
        }

        .order-container {
            margin-bottom: 20px;
            padding: 10px;
            page-break-inside: avoid;
        }

        .customer-info,
        .order-info {
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .product-image {
            width: 50px;
            height: auto;
        }

        .head {
            margin-bottom: 10px;
            position: relative;
            page-break-before: always;
            clear: both;
        }

        .head .head02 {
            float: right; /* จัดให้อยู่ทางขวา */
            width: 100px;
            height: auto;
        }

        .head .head03 {
            float: left; /* จัดให้อยู่ทางซ้าย */
            width: 100px;
            height: auto;
        }
    </style>
</head>

<body>
    @foreach ($orders as $order)
    <div class="head">
        <div class="head03">
            <img src="{{ public_path('img/images.png') }}" alt="logo" style="width: 100px;">
        </div>
        <div class="head02">
            <img src="{{ public_path('img/frame.png') }}" alt="QR" style="width: 50px;">
        </div>
    </div>
    <div class="order-container">
        <h2>ข้อมูลลูกค้า</h2>
        <div class="customer-info">
            <p><strong>ลูกค้าที่สั่งซื้อ:</strong> {{ $order->company }}</p>
            <p><strong>อีเมล:</strong> {{ $order->email }}</p>
            <p><strong>หมายเลขโทรศัพท์:</strong> {{ $order->phone_number }}</p>
            <p><strong>ที่อยู่จัดส่ง:</strong> {{ $order->shipping_address }}</p>
        </div>

        <h2>ข้อมูลคำสั่งซื้อ</h2>
        <div class="order-info">
            <p><strong>หมายเลขคำสั่งซื้อ:</strong> {{ $order->id }}</p>
            <p><strong>วันที่สั่งซื้อ:</strong> {{ $order->created_at }}</p>
            <p><strong>สถานะการจัดส่งสินค้า:</strong>
                @switch($order->status)
                    @case('Shipped')
                        <span class="badge bg-warning">จัดส่งแล้ว</span>
                    @break

                    @default
                        <span class="badge bg-secondary">{{ $order->status }}</span>
                @endswitch
            </p>
            <p><strong>วันที่ชำระเงิน:</strong> {{ $orderDetails[$order->id]['payment_date'] }}</p> <!-- แสดงวันที่เวลาชำระเงิน -->
        </div>

        <h2>รายการสินค้า</h2>
        <table>
            <thead>
                <tr>
                    <th>สินค้า</th>
                    <th>จำนวน</th>
                    <th>ราคา</th>
                    <th>ยอดรวม</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orderDetails[$order->id]['items'] as $item)
                    <tr>
                        <td>{{ $item->pd_name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->price, 2) }}</td>
                        <td>{{ number_format($item->quantity * $item->price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="order-summary" style="text-align: right; margin-top: 10px;">
            <p><strong>ยอดรวม:</strong> {{ number_format($order->total_amount, 2) }} บาท</p>
            <p><strong>ค่าจัดส่งสินค้า:</strong> 38 บาท</p>
            <p><strong>ยอดรวมทั้งสิ้น:</strong> {{ number_format($order->total_amount + 38, 2) }} บาท</p>
        </div>
    </div>
    @endforeach
</body>

</html>
