<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>ชื่อลูกค้า</th>
            <th>วันที่สั่งซื้อ</th>
            <th>ยอดรวม</th>
            <th>สถานะจัดส่งสินค้า</th>
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
                    <a href="{{ route('report.report-order.show', $order->id) }}"
                        class="btn btn-sm btn-info">ดูรายละเอียด</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<div class="pagination-container">
    <ul class="pagination">
        @if ($orders->currentPage() > 1)
            <li class="page-item"><a class="page-link" href="{{ $orders->previousPageUrl() }}">« Previous</a></li>
        @else
            <li class="page-item disabled"><span class="page-link">« Previous</span></li>
        @endif

        @for ($i = 1; $i <= $orders->lastPage(); $i++)
            @if ($i == 1 || $i == $orders->lastPage() || ($i >= $orders->currentPage() - 1 && $i <= $orders->currentPage() + 1))
                @if ($i == $orders->currentPage())
                    <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $orders->url($i) }}">{{ $i }}</a>
                    </li>
                @endif
            @elseif ($i == $orders->currentPage() - 2 || $i == $orders->currentPage() + 2)
                <li class="page-item disabled"><span class="page-link">...</span></li>
            @endif
        @endfor

        @if ($orders->hasMorePages())
            <li class="page-item"><a class="page-link" href="{{ $orders->nextPageUrl() }}">Next »</a></li>
        @else
            <li class="page-item disabled"><span class="page-link">Next »</span></li>
        @endif
    </ul>
</div>
