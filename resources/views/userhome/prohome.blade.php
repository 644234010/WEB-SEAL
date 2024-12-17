<div class="container" id="products-list">
    <div class="row" id="product-cards">
        @foreach ($products as $product)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    @if ($product->pd_image)
                        <img src="{{ asset('storage/' . $product->pd_image) }}" class="card-img-top"
                            alt="{{ $product->pd_name }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="{{ route('userhome.show', $product->id) }}">{{ $product->pd_name }}</a>
                        </h5>
                        <p class="card-text">{{Str::limit($product->pd_detail, 50) }}</p>
                        <p class="card-text">ราคา: {{ number_format($product->pd_price) }} บาท</p>
                        <form class="add-to-cart-form" data-id="{{ $product->id }}" method="POST">
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <button type="button" class="btn btn-primary buy-now-btn" ><i class="bi bi-cart-check"></i> เพิ่มสินค้าลงในตะกร้า</button>
                        </form>                      
                        @include('userhome.cart')  
                    </div>
                </div>
            </div>
        @endforeach

        <div class="d-flex justify-content-center">
            @if ($products->lastPage() > 1)

            <ul class="pagination">
                @if ($products->onFirstPage())
                    <li class="page-item disabled"><span class="page-link">ย้อนกลับ</span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $products->previousPageUrl() }}">ย้อนกลับ</a></li>
                @endif
            
                @for ($i = 1; $i <= $products->lastPage(); $i++)
                    @if ($i == $products->currentPage())
                        <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $products->url($i) }}">{{ $i }}</a></li>
                    @endif
                @endfor
            
                @if ($products->hasMorePages())
                    <li class="page-item"><a class="page-link" href="{{ $products->nextPageUrl() }}">ถัดไป</a></li>
                @else
                    <li class="page-item disabled"><span class="page-link">ถัดไป</span></li>
                @endif
            </ul>            
            @endif
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.buy-now-btn').on('click', function(e) {
            e.preventDefault();
            var button = $(this);
            var form = button.closest('form');
            var productId = form.data('id');
    
            Swal.fire({
                title: 'ยืนยันการซื้อ',
                text: "คุณต้องการซื้อสินค้านี้หรือไม่?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่, ซื้อเลย!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('cart.add', '') }}/' + productId,
                        method: 'POST',
                        data: form.serialize(),
                        success: function(response) {
                            Swal.fire(
                                'สำเร็จ!',
                                'สินค้าได้ถูกเพิ่มลงในรถเข็น.',
                                'success'
                            );
                            // Update the cart count
                            updateCartCount();
                        },
                        error: function(response) {
                            Swal.fire(
                                'ข้อผิดพลาด!',
                                'เกิดข้อผิดพลาดในการเพิ่มสินค้า.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    
        // Function to update the cart count
        function updateCartCount() {
            $.ajax({
                url: '{{ route('cart.count') }}',
                method: 'GET',
                success: function(response) {
                    $('.cart-count').text(response.count);
                },
                error: function(response) {
                    console.error('Error fetching cart count');
                }
            });
        }
    
        // Initial call to update cart count on page load
        updateCartCount();
    });
    </script>
    
    

