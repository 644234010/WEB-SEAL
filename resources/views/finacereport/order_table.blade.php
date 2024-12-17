<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>ชื่อลูกค้า</th>
            <th>วันที่สั่งซื้อ</th>
            <th>ยอดรวม</th>
            <th>สถานะการชำระเงิน</th>
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
                    @switch($order->payment_status)
                        @case('Pending')
                            <span class="badge bg-warning">ปลายทาง</span>
                        @break

                        @case('Unpaid')
                            <span class="badge bg-warning">ยังไม่ได้ชำระ</span>
                        @break

                        @case('Paid')
                            <span class="badge bg-success">ชำระแล้ว</span>
                        @break

                        @case('Rejected')
                            <span class="badge bg-danger">ปฏิเสธการชำระเงิน</span>
                        @break

                        @default
                            <span class="badge bg-secondary">{{ $order->payment_status ?? 'ไม่ทราบสถานะ' }}</span>
                    @endswitch
                </td>
                <td>
                    <a href="{{ route('finacereport.unpaid-ordersdetail', $order->id) }}"
                        class="btn btn-sm btn-info">ดูรายละเอียด</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@include('finacereport.custom_pagination', ['orders' => $orders])

