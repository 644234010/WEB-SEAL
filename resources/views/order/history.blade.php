@extends('layoutsuser.head')
@section('content')
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-light border-bottom-0">
                <h5 class="mb-0">ประวัติการสั่งซื้อและสถานะ</h5>
            </div>
            <div class="card-body">
                @if ($orders->isEmpty())
                    <p class="text-center">คุณยังไม่มีประวัติการสั่งซื้อ</p>
                @else
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>หมายเลขคำสั่งซื้อ</th>
                                <th>วันที่สั่งซื้อ</th>
                                <th>ยอดรวม</th>
                                <th>สถานะการชำระเงิน</th>
                                <th>สถานะการจัดส่ง</th>
                                <th>การดำเนินการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td>{{ $order->id }}</td>
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

                                            @case('Paid')
                                                <span class="badge bg-success">ชำระแล้ว</span>
                                            @break

                                            @case('Rejected')
                                                <span class="badge bg-danger">ปฏิเสธการชำระเงิน</span>
                                            @break

                                            @default
                                                <span
                                                    class="badge bg-secondary">{{ $order->payment_status ?? 'ไม่ทราบสถานะ' }}</span>
                                        @endswitch
                                    </td>
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

                                            @default
                                                <span class="badge bg-secondary">{{ $order->status }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        <a href="{{ route('order.details', $order->id) }}"
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
@endsection
