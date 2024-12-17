<x-app-layout>
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-light border-bottom-0">
                <h5 class="mb-0">รายการสั่งซื้อที่รอดำเนินการชำระเงิน</h5>
            </div>
            <div class="card-body">
                @if ($pendingOrders->isEmpty())
                    <p class="text-center">ไม่มีรายการที่รอดำเนินการชำระเงิน</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>หมายเลขคำสั่งซื้อ</th>
                                    <th>ชื่อลูกค้า</th>
                                    <th>วันที่สั่งซื้อ</th>
                                    <th>ยอดรวม</th>
                                    <th>สถานะการสั่งซื้อ</th>
                                    <th>การดำเนินการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pendingOrders as $order)
                                    <tr>
                                        <td>{{ $order->id }}</td>
                                        <td>{{ $order->customer_name }}</td>
                                        <td>{{ date('d/m/Y', strtotime($order->created_at)) }}</td>
                                        <td>฿{{ number_format($order->total_amount, 2) }}</td>
                                        <td>
                                            @switch($order->status)
                                                @case('Pending')
                                                    <span class="badge bg-warning">รอดำเนินการ</span>
                                                @break

                                                @case('Processing')
                                                    <span class="badge bg-info">กำลังดำเนินการ</span>
                                                @break

                                                @default
                                                    <span class="badge bg-secondary">{{ $order->status }}</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            <a href="{{ route('report.pendingPaymentOrdersdetail', $order->id) }}"
                                                class="btn btn-sm btn-outline-primary">ดูรายละเอียด</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
