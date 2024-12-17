<x-app-layout>
    <div class="container mt-5">

        <h1>รายละเอียดคำสั่งซื้อ</h1>

        <div class="card">
            <div class="card-header">
                <h2>คำสั่งซื้อ #{{ $order->id }}</h2>
            </div>
            <div class="card-body">
                <form action="{{ route('finacereport.update', $order->id) }}" method="POST">
                    @csrf

                    @if (isset($payment) && $payment)
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
                        

                        @if ($payment->slip_filename)
                            <div class="mb-3">
                                <h3>หลักฐานการชำระเงิน</h3>
                                <img src="{{ asset('storage/' . $payment->slip_filename) }}" alt="Payment Slip"
                                    class="img-fluid mb-2" style="max-width: 300px;">
                                <p><strong>วันที่และเวลาที่ชำระ:</strong>
                                    {{ \Carbon\Carbon::parse($payment->payment_date)->timezone('Asia/Bangkok')->format('d/m/Y H:i') }}
                                </p>
                                <p><strong>วันที่และเวลาที่ชำระ:</strong> 
                                    {{ $order->payment_date ? \Carbon\Carbon::parse($order->payment_date)->format('d/m/Y H:i') : '-' }}
                                </p>

                               
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


                    <a href="{{ route('finacereport.unpaid-orders') }}" class="btn btn btn-primary">กลับหน้าจัดการสถานะรายการยังไม่ชำระเงิน</a>
                    <a href="{{ route('finacereport.history') }}"
                        class="btn btn btn-success">กลับหน้าจัดการสถานะรายการสั่งซื้อ</a>
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
            url: '{{ route('finacereport.update', ':id') }}'.replace(':id', orderId),
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                payment_status: paymentStatus
            },
            success: function(response) {
                if (response.success) {
                    alert('อัพเดทสถานะการชำระเงินเรียบร้อยแล้ว');
                    // อัพเดทส่วนอื่นๆ ของหน้าตามความเหมาะสม
                    if (paymentStatus == 'Paid') {
                        $('#status').prop('disabled', false);
                    } else {
                        $('#status').prop('disabled', true);
                    }
                } else {
                    alert('เกิดข้อผิดพลาดในการอัพเดทสถานะ: ' + response.error);
                }
            },
            error: function(xhr, status, error) {
                alert('เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์: ' + error);
            }
        });
    });

            // เพิ่มโค้ดสำหรับอัพเดทสถานะการจัดส่งแบบเรียลไทม์ด้วย
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
