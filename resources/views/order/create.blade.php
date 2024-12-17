@extends('layoutsuser.head')

@section('content')
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-light border-bottom-0">
                <h5 class="mb-0">ทำการสั่งซื้อ</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('order.store') }}" method="POST" enctype="multipart/form-data" id="orderForm">
                    @csrf
                    <!-- ข้อมูลที่อยู่จัดส่ง -->
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2">ที่อยู่จัดส่ง</h6>
                        <select id="address-select" class="form-select mb-2" name="address_select">
                            <option value="">เลือกที่อยู่</option>
                            @foreach ($addresses as $index => $address)
                                <option value="{{ $index }}">{{ $address }}</option>
                            @endforeach
                            <option value="new">เพิ่มที่อยู่ใหม่</option>
                        </select>
                        <input type="text" class="form-control" id="shipping-address" name="shipping_address"
                            placeholder="กรุณากรอกที่อยู่จัดส่งถ้าที่อยู่จัดส่งไม่ตรงกับข้อมูลที่มี" required>
                        <button type="button" id="add-address" class="btn btn-secondary mt-2"
                            style="display: none;">เพิ่มที่อยู่</button>
                    </div>

                    <!-- รายการสินค้า -->
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2">รายการสินค้า</h6>
                        @foreach ($products as $product)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="me-2 flex-grow-1">
                                    <img src="{{ asset('storage/' . $product->pd_image) }}" class="img-thumbnail"
                                        alt="" style="max-width: 100px;">
                                </div>
                                <div class="me-2 flex-grow-1">
                                    <label class="form-label">{{ $product->pd_name }}</label>
                                </div>
                                <div class="me-2 flex-grow-1">
                                    <input type="number" class="form-control product-quantity"
                                        name="products[{{ $product->id }}][quantity]"
                                        value="{{ $cartItems[$product->id] }}" readonly>
                                </div>
                                <div class="me-2 flex-grow-1">
                                    <input type="hidden" name="products[{{ $product->id }}][id]"
                                        value="{{ $product->id }}">
                                    <input type="hidden" class="product-price" name="products[{{ $product->id }}][price]"
                                        value="{{ $product->pd_price }}">
                                    <span
                                        class="form-control-plaintext">฿{{ $product->pd_price * $cartItems[$product->id] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- สรุปยอด -->
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2">สรุปยอด</h6>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>รวมการสั่งซื้อ:</span>
                            <span>฿<span id="total-amount">0</span></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>รวมการจัดส่ง:</span>
                            <span>฿38</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>ยอดชำระเงินทั้งหมด:</span>
                            <span>฿<span id="final-amount">0</span></span>
                        </div>
                        <input type="hidden" name="total_amount" id="hidden-total-amount" value="{{ $finalTotal }}">
                    </div>

                    <div class="mb-4">
                        <h6 class="border-bottom pb-2">วิธีการชำระเงิน</h6>
                        <p>ชำระเงินได้ทางช่องทางต่อไปนี้:</p>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" id="qr_payment"
                                value="qr_payment" required>
                            <label class="form-check-label" for="qr_payment">
                                ชำระผ่านระบบ PromptPay QR
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" id="cash_on_delivery"
                                value="cash_on_delivery" required>
                            <label class="form-check-label" for="cash_on_delivery">
                                ชำระปลายทาง
                            </label>
                        </div>
                    </div>

                    <div id="qrPaymentSection" style="display: none;">
                        <img src="{{ asset('img/QR.jpeg') }}" alt="PromptPay QR" class="img-fluid mt-2" width="200">
                        <div class="form-group mt-3">
                            <label for="proof_of_payment" class="form-label">อัพโหลดสลิปการโอนเงิน</label>
                            <input type="file" class="form-control" name="proof_of_payment" id="proof_of_payment">
                        </div>

                        <div class="form-group mt-3">
                            <label for="payment_datetime" class="form-label">วันที่และเวลาที่ชำระเงิน</label>
                            <input type="datetime-local" class="form-control" name="payment_datetime" id="payment_datetime"
                                required>
                        </div>

                        <div class="form-group mt-3">
                            <label for="expected_name" class="form-label">ชื่อ</label>
                            <input type="text" class="form-control" id="expected_name" value="นาย กิตติภัฏ ชูบัว"
                                readonly>
                        </div>
                        <div class="form-group mt-3">
                            <label for="expected_account" class="form-label">เลขบัญชีท้าย</label>
                            <input type="text" class="form-control" id="expected_account" value="3125" readonly>
                        </div>
                    </div>

                    <!-- ปุ่มยืนยันคำสั่งซื้อ -->
                    <div class="text-end">
                        <button type="submit" class="btn btn-danger">ยืนยันคำสั่งซื้อ</button>
                        <a href="{{ route('cart.index') }}" class="btn btn-secondary">กลับสู่หน้ารถเข็น</a>
                    </div>
                </form>

                <script src="https://unpkg.com/tesseract.js@v2.1.0/dist/tesseract.min.js"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const qrPaymentRadio = document.getElementById('qr_payment');
                        const cashOnDeliveryRadio = document.getElementById('cash_on_delivery');
                        const qrPaymentSection = document.getElementById('qrPaymentSection');
                        const proofOfPayment = document.getElementById('proof_of_payment');
                        const orderForm = document.getElementById('orderForm');
                        const addressSelect = document.getElementById('address-select');
                        const shippingAddress = document.getElementById('shipping-address');
                        const addAddressBtn = document.getElementById('add-address');

                        qrPaymentRadio.addEventListener('change', function() {
                            if (this.checked) {
                                qrPaymentSection.style.display = 'block';
                                proofOfPayment.required = true;
                            }
                        });
                        cashOnDeliveryRadio.addEventListener('change', function() {
                            if (this.checked) {
                                qrPaymentSection.style.display = 'none';
                                proofOfPayment.required = false;
                                document.getElementById('payment_datetime').required =
                                    false; // ปิดการบังคับกรอกวันที่และเวลาชำระเงิน
                            }
                        });
                        addressSelect.addEventListener('change', function() {
                            if (this.value === 'new') {
                                shippingAddress.value = '';
                                shippingAddress.readOnly = false;
                                addAddressBtn.style.display = 'inline-block';
                            } else if (this.value !== '') {
                                shippingAddress.value = this.options[this.selectedIndex].text;
                                shippingAddress.readOnly = true;
                                addAddressBtn.style.display = 'none';
                            } else {
                                shippingAddress.value = '';
                                shippingAddress.readOnly = true;
                                addAddressBtn.style.display = 'none';
                            }
                        });

                        addAddressBtn.addEventListener('click', function() {
                            const newAddress = shippingAddress.value.trim();
                            if (newAddress) {
                                const newOption = new Option(newAddress, addressSelect.options.length);
                                addressSelect.add(newOption);
                                addressSelect.value = addressSelect.options.length - 1;
                                addAddressBtn.style.display = 'none';
                                shippingAddress.readOnly = true;
                            }
                        });

                        orderForm.addEventListener('submit', function(e) {
                            e.preventDefault();

                            if (qrPaymentRadio.checked) {
                                if (!proofOfPayment.files.length) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'เกิดข้อผิดพลาด',
                                        text: 'กรุณาอัพโหลดสลิปการโอนเงิน'
                                    });
                                    return;
                                }

                                const paymentDatetime = document.getElementById('payment_datetime');
                                if (!paymentDatetime.value) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'เกิดข้อผิดพลาด',
                                        text: 'กรุณาระบุวันที่และเวลาที่ชำระเงิน'
                                    });
                                    return;
                                }
                                Swal.fire({
                                    title: 'กำลังตรวจสอบสลิปการโอนเงิน...',
                                    text: 'โปรดรอสักครู่',
                                    allowOutsideClick: false,
                                    showConfirmButton: false,
                                    didOpen: () => {
                                        Swal.showLoading();
                                        verifyImage(proofOfPayment.files[0])
                                            .then(result => {
                                                if (result.isValid) {
                                                    Swal.fire({
                                                        icon: 'success',
                                                        title: 'การชำระเงินของคุณเสร็จสมบูรณ์แล้ว',
                                                        showConfirmButton: false,
                                                        timer: 1500
                                                    }).then(() => {
                                                        submitOrderAjax();
                                                    });
                                                } else {
                                                    Swal.fire({
                                                        icon: 'error',
                                                        title: 'ข้อมูลไม่ตรง',
                                                        text: 'ข้อมูลในสลิปไม่ตรง กรุณาตรวจสอบและลองใหม่อีกครั้ง'
                                                    });
                                                }
                                            })
                                            .catch(error => {
                                                console.error('Verification Error:', error);
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'เกิดข้อผิดพลาด',
                                                    text: 'เกิดข้อผิดพลาดในการตรวจสอบภาพ กรุณาลองใหม่อีกครั้ง'
                                                });
                                            });
                                    }
                                });
                            } else {
                                Swal.fire({
                                    icon: 'question',
                                    title: 'ยืนยันการสั่งซื้อ',
                                    text: 'คุณต้องการยืนยันคำสั่งซื้อนี้หรือไม่?',
                                    showCancelButton: true,
                                    confirmButtonText: 'ยืนยัน',
                                    cancelButtonText: 'ยกเลิก'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        submitOrderAjax();
                                    }
                                });
                            }
                        });

                        function submitOrderAjax() {
                            const formData = new FormData(orderForm);

                            $.ajax({
                                url: orderForm.action,
                                method: 'POST',
                                data: formData,
                                processData: false,
                                contentType: false,
                                success: function(response) {
                                    if (response.success) {
                                        window.location.href = response.redirect;
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'เกิดข้อผิดพลาด',
                                            text: response.message ||
                                                'เกิดข้อผิดพลาดในการสั่งซื้อ กรุณาลองใหม่อีกครั้ง'
                                        });
                                    }
                                },
                                error: function(xhr) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'เกิดข้อผิดพลาด',
                                        text: 'เกิดข้อผิดพลาดในการสั่งซื้อ กรุณาลองใหม่อีกครั้ง'
                                    });
                                }
                            });
                        }

                        calculateTotal();
                    });

                    function calculateTotal() {
                        let total = 0;
                        document.querySelectorAll('.product-quantity').forEach(function(input) {
                            let quantity = parseInt(input.value);
                            let price = parseFloat(input.closest('.d-flex').querySelector('.product-price').value);
                            total += quantity * price;
                        });

                        let shippingCost = 38;
                        let finalTotal = total + shippingCost;

                        document.getElementById('total-amount').textContent = total.toFixed(2);
                        document.getElementById('final-amount').textContent = finalTotal.toFixed(2);
                        document.getElementById('hidden-total-amount').value = finalTotal.toFixed(2);
                    }

                    function verifyImage(file) {
                        return new Promise((resolve, reject) => {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                const img = new Image();
                                img.onload = function() {
                                    const canvas = document.createElement('canvas');
                                    const ctx = canvas.getContext('2d');
                                    canvas.width = img.width;
                                    canvas.height = img.height;
                                    ctx.drawImage(img, 0, 0);

                                    Tesseract.recognize(canvas.toDataURL('image/jpeg'), 'tha+eng')
                                        .then(({
                                            data: {
                                                text
                                            }
                                        }) => {
                                            console.log('OCR Result:', text);

                                            const expectedAccount = document.getElementById('expected_account')
                                                .value;
                                            const expectedAmount = document.getElementById('final-amount')
                                                .textContent.replace('.', '');

                                            const cleanText = text.replace(/[^ก-๙a-zA-Z0-9]/g, '').toLowerCase();

                                            const accountMatch = new RegExp(expectedAccount).test(cleanText);
                                            const amountMatch = new RegExp(expectedAmount).test(cleanText);

                                            const result = {
                                                isValid: accountMatch && amountMatch,
                                                details: {
                                                    accountMatch,
                                                    amountMatch,
                                                    text: cleanText
                                                }
                                            };

                                            resolve(result);
                                        })
                                        .catch(error => {
                                            console.error('OCR Error:', error);
                                            reject(error);
                                        });
                                };
                                img.src = e.target.result;
                            };
                            reader.readAsDataURL(file);
                        });

                    }
                </script>
            </div>
        </div>
    </div>
@endsection
