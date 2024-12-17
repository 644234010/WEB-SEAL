<x-app-layout>
    <div class="container mt-5">
        <h1>รายละเอียดคำสั่งซื้อ</h1>

        <div class="card">
            <div class="card-header">
                <h2>คำสั่งซื้อ #{{ $order->id }}</h2>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="status" class="form-label">สถานะการจัดส่ง</label>
                    <select name="status" id="status" class="form-control"
                        {{ isset($payment) && $payment->payment_status == 'Paid' ? '' : 'disabled' }}>
                        <option value="Pending" {{ $order->status == 'Pending' ? 'selected' : '' }}>รอดำเนินการ</option>
                        <option value="Processing" {{ $order->status == 'Processing' ? 'selected' : '' }}>กำลังดำเนินการ
                        </option>
                        <option value="Shipped" {{ $order->status == 'Shipped' ? 'selected' : '' }}>จัดส่งแล้ว</option>
                        <option value="Rejected" {{ $order->status == 'Rejected' ? 'selected' : '' }}>ปฏิเสธการดำเนินการ
                        </option>
                    </select>
                </div>



                <div id="updateStatus" class="alert" style="display: none;"></div>
                <a href="{{ route('Shippingreport.pendingPaymentOrders') }}"
                    class="btn btn-success">กลับรายการสั่งซื้อล่าสุดที่รอดำเนินการ</a>
                <a href="{{ route('Shippingreport.history') }}"
                    class="btn btn-success">กลับหน้าจัดการสถานะการจัดจัดส่ง</a>
            </div>
        </div>




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
                            <td><img src="{{ asset('storage/' . $item->pd_image) }}" alt="{{ $item->pd_name }}"
                                    width="50"></td>
                            <td>{{ $item->pd_name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>฿{{ number_format($item->price, 2) }}</td>
                            <td>฿{{ number_format($item->quantity * $item->price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <script>
            $(document).ready(function() {
                function updateOrder(status) {
                    $.ajax({
                        url: '{{ route('Shippingreport.update', $order->id) }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            status: status
                        },
                        success: function(response) {
                            if (response.success) {
                                showUpdateStatus('อัพเดทสถานะเรียบร้อยแล้ว', 'success');
                            } else {
                                showUpdateStatus('เกิดข้อผิดพลาด: ' + (response.error || 'ไม่ทราบสาเหตุ'),
                                    'danger');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error details:', xhr.responseText);
                            let errorMessage = 'เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์';
                            if (xhr.responseJSON && xhr.responseJSON.error) {
                                errorMessage += ': ' + xhr.responseJSON.error;
                            } else {
                                errorMessage += ': ' + error;
                            }
                            showUpdateStatus(errorMessage, 'danger');
                        }
                    });
                }

                function pollOrderStatus() {
                    $.ajax({
                        url: '{{ route('Shippingreport.getStatus', $order->id) }}',
                        method: 'GET',
                        success: function(response) {
                            if (response.success && response.order.status !== $('#status').val()) {
                                $('#status').val(response.order.status);
                                showUpdateStatus('สถานะได้รับการอัพเดทจากระบบ', 'info');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error polling order status:', error);
                        },
                        complete: function() {
                            setTimeout(pollOrderStatus, 30000); // Poll every 30 seconds
                        }
                    });
                }

                function showUpdateStatus(message, type) {
                    $('#updateStatus').removeClass('alert-success alert-danger alert-info').addClass('alert-' + type)
                        .text(message).show();
                    setTimeout(function() {
                        $('#updateStatus').fadeOut();
                    }, 3000);
                }

                $('#status').on('change', function() {
                    updateOrder($(this).val());
                });

                pollOrderStatus(); // Start polling
            });
        </script>
    </div>
</x-app-layout>
