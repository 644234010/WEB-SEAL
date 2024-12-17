<x-app-layout>
    <div class="container mt-5">
        <h1>รายละเอียดคำสั่งซื้อ</h1>

        <div class="card">
            <div class="card-header">
                <h2>คำสั่งซื้อ #{{ $order->id }}</h2>
            </div>
            <div class="card-body">

                <div class="card-body">
                    <form action="{{ route('update.order', $order->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="payment_status" class="form-label">สถานะการชำระเงิน</label>
                            <select name="payment_status" id="payment_status" class="form-control">
                                <option value="Unpaid" {{ $payment->payment_status == 'Unpaid' ? 'selected' : '' }}>
                                    ยังไม่ได้ชำระ</option>
                                <option value="Paid" {{ $payment->payment_status == 'Paid' ? 'selected' : '' }}>
                                    ชำระแล้ว</option>
                                <option value="Rejected" {{ $payment->payment_status == 'Rejected' ? 'selected' : '' }}>
                                    ปฏิเสธการชำระเงิน</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">สถานะการจัดส่ง</label>
                            <select name="status" id="status" class="form-control"
                                {{ isset($payment) && $payment->payment_status == 'Paid' ? '' : 'disabled' }}>
                                <option value="Pending" {{ $order->status == 'Pending' ? 'selected' : '' }}>รอดำเนินการ
                                </option>
                                <option value="Processing" {{ $order->status == 'Processing' ? 'selected' : '' }}>
                                    กำลังดำเนินการ</option>
                                <option value="Shipped" {{ $order->status == 'Shipped' ? 'selected' : '' }}>จัดส่งแล้ว
                                </option>
                                <option value="Rejected" {{ $order->status == 'Rejected' ? 'selected' : '' }}>
                                    ปฏิเสธการดำเนินการ</option>
                            </select>
                        </div>
                        @if (isset($payment) && $payment)


                            @if ($payment->slip_filename)
                                <div class="mb-3">
                                    <h3>หลักฐานการชำระเงิน</h3>
                                    <img src="{{ asset('storage/' . $payment->slip_filename) }}" alt="Payment Slip"
                                        class="img-fluid mb-2" style="max-width: 300px;">

                                </div>
                            @else
                                <p>ไม่พบหลักฐานการชำระเงิน</p>
                            @endif
                        @else
                            <p>ไม่พบข้อมูลการชำระเงิน</p>
                        @endif


                        <!-- แสดงรายการสินค้า -->
                        <div class="mb-3">
                            <h3>รายการสินค้า</h3>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>รูปภาพ</th>
                                        <th>ชื่อสินค้า</th>
                                        <th>จำนวน</th>
                                        <th>ราคาต่อชิ้น</th>
                                        <th>ราคารวม</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orderItems as $item)
                                        <tr>
                                            <td><img src="{{ asset('storage/' . $item->pd_image) }}"
                                                    alt="{{ $item->pd_name }}" width="50"></td>
                                            <td>{{ $item->pd_name }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>฿{{ number_format($item->price, 2) }}</td>
                                            <td>฿{{ number_format($item->quantity * $item->price, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <a href="{{ route('report.unpaid-orders') }}" class="btn btn-primary">อัพเดท</a>
                        <a href="{{ route('report.unpaid-orders') }}" class="btn btn-success">กลับรายการสั่งซื้อล่าสุดที่ยังไม่ได้ชำระเงิน</a>
                    </form>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                $('#payment_status').change(function() {
                    var paymentStatus = $(this).val();
                    var orderId = {{ $order->id }};

                    $.ajax({
                        url: '{{ route('update.order', ':id') }}'.replace(':id', orderId),
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            payment_status: paymentStatus
                        },
                        success: function(response) {
                            if (response.success) {
                                alert('อัพเดทสถานะการชำระเงินเรียบร้อยแล้ว');

                                if (paymentStatus == 'Paid') {
                                    $('#status').prop('disabled', false);
                                } else {
                                    $('#status').prop('disabled', true);
                                }
                            } else {
                                alert('เกิดข้อผิดพลาดในการอัพเดทสถานะ');
                            }
                        },
                        error: function() {
                            alert('เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์');
                        }
                    });
                });


                $('#status').change(function() {
                    var orderStatus = $(this).val();
                    var orderId = {{ $order->id }};

                    $.ajax({
                        url: '{{ route('update.order', ':id') }}'.replace(':id', orderId),
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            status: orderStatus
                        },
                        success: function(response) {
                            if (response.success) {
                                alert('อัพเดทสถานะการจัดส่งเรียบร้อยแล้ว');
                            } else {
                                alert('เกิดข้อผิดพลาดในการอัพเดทสถานะ');
                            }
                        },
                        error: function() {
                            alert('เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์');
                        }
                    });
                });
            });
        </script>
</x-app-layout>
