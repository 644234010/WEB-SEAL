@extends('layoutsuser.head')

@section('title', 'รายละเอียดสินค้า')

@section('content')
<div class="container mt-5">
    <div class="card mb-4 shadow-sm">
        <div class="row">
            <div class="col-md-6">
                <div class="main-image mb-3">
                    @if ($product->pd_image)
                        <img src="{{ asset('storage/' . $product->pd_image) }}" class="img-fluid rounded" alt="{{ $product->pd_name }}" id="mainImage">
                    @else
                        <div class="placeholder">ไม่มีรูปภาพ</div>
                    @endif
                </div>
                <div class="thumbnail-images d-flex justify-content-between">
                    @foreach (['pd_image_1', 'pd_image_2', 'pd_image_3'] as $imageField)
                        @if ($product->$imageField)
                            <img src="{{ asset('storage/' . $product->$imageField) }}" class="img-thumbnail" alt="Thumbnail" onclick="document.getElementById('mainImage').src=this.src">
                        @else
                            <div class="thumbnail placeholder">ไม่มีรูปภาพ</div>
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="col-md-6">
                <div class="product-details">
                    <p></p>
                    <h5 class="product-title mb-3">ชื่อสินค้า: {{ $product->pd_name }}</h5>
                    <h6 class="product-color mb-3">สี: {{ $product->pd_color }}</h6>
                    <p class="product-description mb-3">รายละเอียดสินค้า: {{ $product->pd_detail }}</p>
                    <p class="product-price text-success mb-3">ราคา: <strong>{{ number_format($product->pd_price) }} บาท</strong></p>
                    <p class="product-stock mb-3">จำนวนสินค้าคงเหลือ: {{ $product->pd_stock }}</p>
                    <p class="product-category mb-4">แบรน: {{ $product->categories_name }}</p>
                    
                    <form class="add-to-cart-form" data-id="{{ $product->id }}" method="POST">
                        @csrf
                        <button type="button" class="btn btn-primary btn-block add-to-cart-btn"><i class="bi bi-cart-check"></i> เพิ่มลงตะกร้า</button>
                    </form>
                </div>
                @include('userhome.cart')
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <img src="" id="modalImage" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#mainImage, .thumbnail-images img').on('click', function() {
            $('#modalImage').attr('src', $(this).attr('src'));
            $('#imageModal').modal('show');
        });

        $('.add-to-cart-btn').on('click', function(e) {
            e.preventDefault();
            var button = $(this);
            var form = button.closest('form');
            var productId = form.data('id');

            $.ajax({
                url: '{{ route('cart.add', '') }}/' + productId,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    Swal.fire(
                        'สำเร็จ!',
                        'สินค้าได้ถูกเพิ่มลงในรถเข็น.',
                        'success'
                    );
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
        });

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

        updateCartCount();
    });
</script>

<style>
    .main-image {
        height: 400px;
        background-color: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #dee2e6;
        overflow: hidden;
    }

    .placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #e9ecef;
        color: #adb5bd;
        font-size: 1.5rem;
        text-align: center;
    }

    .thumbnail-images img {
        width: 30%;
        height: 80px;
        object-fit: cover;
        cursor: pointer;
    }

    .product-title {
        font-size: 1.75rem;
        font-weight: bold;
    }

    .product-description {
        font-size: 1.15rem;
    }

    .product-price {
        font-size: 1.35rem;
        font-weight: bold;
    }

    .btn {
        padding: 10px 20px;
        font-size: 1rem;
    }

    .add-to-cart-btn {
        background-color: #007bff;
        border: none;
        color: white;
    }

    .add-to-cart-btn:hover {
        opacity: 0.8;
    }
</style>
@endsection
