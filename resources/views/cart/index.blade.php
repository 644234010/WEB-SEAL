@extends('layoutsuser.head')

@section('content')
    <div class="container mt-4">
        <h2>รถเข็นของคุณ</h2>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">รูปสินค้า</th>
                        <th scope="col">ชื่อสินค้า</th>
                        <th scope="col">สี</th>
                        <th scope="col">ราคา</th>
                        <th scope="col">จำนวน</th>
                        <th scope="col">ราคารวม</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr data-id="{{ $product->id }}">
                            <td>
                                <img src="{{ asset('storage/' . $product->pd_image) }}" alt="{{ $product->pd_name }}"
                                    style="width: 100px;">
                            </td>
                            <td>{{ $product->pd_name }}</td>
                            <td>{{ $product->pd_color }}</td>
                            <td>{{ number_format($product->pd_price) }} บาท</td>
                            <td>
                                <input type="number" value="{{ $cartItems[$product->id] }}" min="1"
                                    class="form-control quantity-input">
                            </td>
                            <td class="subtotal" data-id="{{ $product->id }}">
                                {{ number_format($product->pd_price * $cartItems[$product->id]) }} </td>
                            <td>
                                <button type="button" class="btn btn-danger remove-btn" data-id="{{ $product->id }}"><i
                                        class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="text-right">
            <h4 class="total-amount">รวม: <span id="total">{{ number_format($total) }}</span> บาท</h4>
            <button class="btn btn-success checkout-btn">ยืนยันการสั่งซื้อสินค้า</button>
            <button class="btn btn-secondary back-btn" id="back-btn">กลับไปยังหน้าสินค้าทั้งหมด</button>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            function updateCheckoutButtonState() {
                var totalQuantity = 0;
                $('.quantity-input').each(function() {
                    totalQuantity += parseInt($(this).val());
                });
                if (totalQuantity === 0) {
                    $('.checkout-btn').prop('disabled', true);
                } else {
                    $('.checkout-btn').prop('disabled', false);
                }
            }

            // Initial state check
            updateCheckoutButtonState();

            // On quantity change
            $('.quantity-input').on('change', function() {
                var id = $(this).closest('tr').data('id');
                var quantity = $(this).val();

                $.ajax({
                    url: '/cart/update/' + id,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        quantity: quantity
                    },
                    success: function(response) {
                        if (response.success) {
                            var formattedSubtotal = new Intl.NumberFormat().format(response
                                .subtotal) + ' ';
                            var formattedTotal = new Intl.NumberFormat().format(response
                                .total) + ' ';

                            $('.subtotal[data-id="' + id + '"]').text(formattedSubtotal);
                            $('#total').text(formattedTotal);
                            updateCheckoutButtonState(); // Update the button state after change
                        } else {
                            alert('Error updating cart');
                        }
                    }
                });
            });

            $('.remove-btn').on('click', function() {
                var id = $(this).data('id');
                var row = $(this).closest('tr');

                Swal.fire({
                    title: 'คุณแน่ใจหรือไม่?',
                    text: "คุณต้องการลบสินค้านี้ออกจากรถเข็นหรือไม่?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'ใช่, ลบมัน!',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/cart/remove/' + id,
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                _method: 'DELETE'
                            },
                            success: function(response) {
                                if (response.success) {
                                    row.remove();
                                    $('#total').text(new Intl.NumberFormat().format(
                                        response.total) + ' ');
                                    updateCheckoutButtonState
                                (); // Update the button state after removal
                                    Swal.fire(
                                        'ลบแล้ว!',
                                        'สินค้าของคุณถูกลบออกจากรถเข็นแล้ว.',
                                        'success'
                                    );
                                } else {
                                    Swal.fire(
                                        'เกิดข้อผิดพลาด!',
                                        'ไม่สามารถลบสินค้าได้.',
                                        'error'
                                    );
                                }
                            }
                        });
                    }
                });
            });

            $('.checkout-btn').on('click', function() {
                Swal.fire({
                    title: 'ยืนยันการสั่งซื้อ?',
                    text: "คุณต้องการยืนยันการสั่งซื้อสินค้านี้หรือไม่?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'ใช่, สั่งสินค้า!',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('order.create') }}";
                    }
                });
            });

            $('.back-btn').on('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'คุณแน่ใจหรือไม่?',
                    text: "คุณต้องการกลับไปยังหน้าสินค้าทั้งหมดหรือไม่?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'ใช่, กลับไป!',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('userhome.products') }}";
                    }
                });
            });
        });
    </script>
@endsection

<style>
    .cart-icon {
        font-size: 2rem;
        cursor: pointer;
    }

    #cart {
        position: fixed;
        bottom: 0;
        right: -2%;
        margin: 70px;
        background-color: #f8f9fa;
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 8px;
        box-shadow: 0 2px 9px rgba(0, 0, 0, 0.1);
    }

    .total-amount {
        text-align: right;
    }

    .quantity-input {
        width: 60px !important;
        max-width: 100%;
    }
</style>
