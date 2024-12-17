<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>ชื่อลูกค้า</th>
            <th>วันที่สั่งซื้อ</th>
            <th>ยอดรวม</th>
            <th>สถานะจัดส่งสินค้า</th>
            <th>การดำเนินการ</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->customer_name }}</td>
                <td>{{ $order->created_at }}</td>
                <td>{{ $order->total_amount }}</td>
                <td>
                    @switch($order->status)
                        @case('Pending')
                            <span class="badge bg-warning">รอดำเนินการ</span>
                        @break

                        @case('Processing')
                            <span class="badge bg-info">กำลังดำเนินการ</span>
                        @break

                        @case('Shipped')
                            <span class="badge bg-primary">จัดส่งแล้ว</span>
                        @break

                        @case('Rejected')
                            <span class="badge bg-danger">ปฏิเสธการดำเนินการ</span>
                        @break

                        @default
                            <span class="badge bg-secondary">{{ $order->status }}</span>
                    @endswitch
                </td>
                <td>
                    <a href="{{ route('Shippingreport.pendingPaymentOrdersdetail', $order->id) }}"
                        class="btn btn-sm btn-info">ดูรายละเอียด</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@include('Shippingreport.custom_pagination', ['orders' => $orders])

