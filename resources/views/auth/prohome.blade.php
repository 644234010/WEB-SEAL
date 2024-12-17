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
                            <a href="{{ route('auth.show', $product->id) }}">{{ $product->pd_name }}</a>
                        </h5>
                        <p class="card-text">{{Str::limit($product->pd_detail, 50) }}</p>
                        <p class="card-text">ราคา: {{ number_format($product->pd_price) }} บาท</p>
                        {{-- <form class="add-to-cart-form" data-id="{{ $product->id }}" method="POST">
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <button type="button" class="btn btn-primary buy-now-btn">เพิ่มสินค้าลงในตะกร้า</button>
                        </form>--}}
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
 
    </script>
    
    

