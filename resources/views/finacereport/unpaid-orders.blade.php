<x-app-layout>
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-light border-bottom-0">
                <h5 class="mb-0">รายการสั่งซื้อที่ยังไม่ได้ชำระเงิน</h5>
            </div>
            <div class="card-body">
                @if ($unpaidOrders->isEmpty())
                    <p class="text-center">ไม่มีรายการที่ยังไม่ได้ชำระเงิน</p>
                @else
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>หมายเลขคำสั่งซื้อ</th>
                                <th>ชื่อลูกค้า</th>
                                <th>วันที่สั่งซื้อ</th>
                                <th>ยอดรวม</th>
                                <th>สถานะการชำระเงิน</th>
                                <th>การดำเนินการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($unpaidOrders as $order)
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->customer_name }}</td>
                                    <td>{{ date('d/m/Y', strtotime($order->created_at)) }}</td>
                                    <td>฿{{ number_format($order->total_amount, 2) }}</td>
                                    <td>
                                        @switch($order->payment_status)
                                            @case('Pending')
                                                <span class="badge bg-warning">ปลายทาง</span>
                                            @break

                                            @case('Unpaid')
                                                <span class="badge bg-warning">ยังไม่ได้ชำระ</span>
                                            @break

                                            <span
                                                class="badge bg-secondary">{{ $order->payment_status ?? 'ไม่ทราบสถานะ' }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        <a href="{{ route('finacereport.unpaid-ordersdetail', $order->id) }}"
                                            class="btn btn-sm btn-outline-primary">ดูรายละเอียด</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
