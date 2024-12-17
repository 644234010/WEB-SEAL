<x-app-layout>
    <div class="card mt-4">
        <div class="container ">
            <h2 class="display-4 font-weight-bold text-primary">รายการสินค้าทั้งหมด</h2>
            <hr class="my-4">
            <form class="d-flex" role="search" method="POST" action="{{ route('inventory.search') }}"
                onsubmit="return false;">
                @csrf
                <input class="form-control me-2" type="search" name="query" placeholder="ค้นหา" aria-label="Search"
                    id="search-input">
            </form>
            <p></p>
            <div class="btn-group">
                <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    แบรน
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item filter-category" href="#" data-category-id="all">ทั้งหมด</a></li>
                    @foreach ($categories as $category)
                        <li><a class="dropdown-item filter-category" href="#" data-category-id="{{ $category->id }}">{{ $category->categories_name }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="d-flex justify-content-end mb-4">
                <button class="btn btn-primary" id="add-product-button">เพิ่มรายการสินค้า</button>

            </div>
        </div>
        <p></p>
        <div class="table-responsive" id="products-list">
            @include('inventory.products_list', ['productshoes' => $productshoes])
        </div>
    </div>

    <!-- Bootstrap Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">เพิ่มข้อมูลสินค้า</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addProductForm">
                        <input type="hidden" id="_token" value="{{ csrf_token() }}">

                        <div class="form-group">
                            <label for="pd_name">ชื่อสินค้า</label>
                            <input type="text" name="pd_name" id="pd_name" class="form-control"
                                value="{{ old('pd_name') }}">
                            <div class="my-2 text-danger" id="error_pd_name"></div>
                        </div>

                        <div class="form-group">
                            <label for="pd_color">รายละเอียดสี</label>
                            <textarea name="pd_color" id="pd_color" cols="30" rows="5" class="form-control">{{ old('pd_color') }}</textarea>
                            <div class="my-2 text-danger" id="error_pd_color"></div>
                        </div>

                        <div class="form-group">
                            <label for="pd_detail">รายละเอียดสินค้า</label>
                            <textarea name="pd_detail" id="pd_detail" cols="30" rows="5" class="form-control">{{ old('pd_detail') }}</textarea>
                            <div class="my-2 text-danger" id="error_pd_detail"></div>
                        </div>

                        <div class="form-group">
                            <label for="pd_price">ราคาสินค้า</label>
                            <input type="text" name="pd_price" id="pd_price" class="form-control"
                                value="{{ old('pd_price') }}">
                            <div class="my-2 text-danger" id="error_pd_price"></div>
                        </div>

                        <div class="form-group">
                            <label for="pd_stock">จำนวนสินค้าคงเหลือ</label>
                            <input type="text" name="pd_stock" id="pd_stock" class="form-control"
                                value="{{ old('pd_stock') }}">
                            <div class="my-2 text-danger" id="error_pd_stock"></div>
                        </div>

                        <div class="form-group">
                            <label for="category_id">แบรน</label>
                            <select name="category_id" id="category_id" class="form-control">
                                <option value="">เลือกแบรน</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->categories_name }}</option>
                                @endforeach
                            </select>
                            <div class="my-2 text-danger" id="error_category_id"></div>
                        </div>

                        <div class="form-group">
                            <label for="pd_image">รูปสินค้า</label>
                            <input type="file" name="pd_image" id="pd_image" class="form-control">
                            <div class="my-2 text-danger" id="error_pd_image"></div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                            <button type="submit" class="btn btn-primary">บันทึก</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            function fetch_data(page, query, categoryId) {
                $.ajax({
                    url: "{{ route('inventory.search') }}",
                    method: "POST",
                    data: {
                        query: query,
                        category_id: categoryId,
                        page: page,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        $('#products-list').html(data);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching data:", error);
                    }
                });
            }

            $('#search-input').on('keyup', function() {
                var query = $(this).val();
                var categoryId = $('.filter-category.active').data('category-id') || 'all';
                fetch_data(1, query, categoryId);
            });

            $(document).on('click', '.pagination a', function(event) {
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                var query = $('#search-input').val();
                var categoryId = $('.filter-category.active').data('category-id') || 'all';
                fetch_data(page, query, categoryId);
            });

            $('#add-product-button').on('click', function() {
                $('#addProductModal').modal('show');
            });


            $('#addProductModal .close, .btn-secondary').on('click', function() {
                $('#addProductModal').modal('hide');
            });

            // Submit product data
            $('#addProductForm').on('submit', function(event) {
                event.preventDefault();

                var pd_name = $('#pd_name').val();
                var pd_detail = $('#pd_detail').val();
                var pd_price = $('#pd_price').val();
                var pd_stock = $('#pd_stock').val();
                var category_id = $('#category_id').val();
                var pd_color = $('#pd_color').val();
                var pd_image = $('#pd_image')[0].files[0];
                var token = $('#_token').val();

                var formData = new FormData();
                formData.append('pd_name', pd_name);
                formData.append('pd_detail', pd_detail);
                formData.append('pd_price', pd_price);
                formData.append('pd_stock', pd_stock);
                formData.append('category_id', category_id);
                formData.append('pd_color', pd_color);
                formData.append('pd_image', pd_image);
                formData.append('_token', token);

                $.ajax({
                    url: "{{ route('inventory.insert') }}",
                    method: "POST",
                    data: formData,
                    dataType: 'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (data.success) {
                            Swal.fire("บันทึกเรียบร้อย!", "", "success");
                            // Clear the form fields
                            $('#addProductForm')[0].reset();
                            // Refresh or update the product list
                            fetch_data(1, $('#search-input').val());
                        }
                    },
                    error: function(response) {
                        if (response.responseJSON && response.responseJSON.errors) {
                            $('#error_pd_name').text(response.responseJSON.errors.pd_name ||
                                '');
                            $('#error_pd_detail').text(response.responseJSON.errors.pd_detail ||
                                '');
                            $('#error_pd_price').text(response.responseJSON.errors.pd_price ||
                                '');
                            $('#error_pd_stock').text(response.responseJSON.errors.pd_stock ||
                                '');
                            $('#error_category_id').text(response.responseJSON.errors
                                .category_id || '');
                            $('#error_pd_color').text(response.responseJSON.errors.pd_color ||
                                '');
                            $('#error_pd_image').text(response.responseJSON.errors.pd_image ||
                                '');
                        } else {
                            console.error('Unexpected error response:', response);
                            // อาจจะแสดงข้อความผิดพลาดทั่วไปให้ผู้ใช้
                            alert('เกิดข้อผิดพลาดที่ไม่คาดคิด กรุณาลองใหม่อีกครั้ง');
                        }
                    }
                });
            });
        });


        function filterProducts(categoryId) {
            var query = $('#search-input').val();
            fetch_data(1, query, categoryId);
            $('.filter-category').removeClass('active');
            $('.filter-category[data-category-id="' + categoryId + '"]').addClass('active');
        }

        $(document).ready(function() {
    $('.filter-category').on('click', function(e) {
        e.preventDefault();
        var categoryId = $(this).data('category-id');
        
        $.ajax({
            url: '/inventory/filter-by-categorys/' + categoryId,
            method: 'GET',
            success: function(data) {
                $('#products-list').html(data);
            },
            error: function(xhr, status, error) {
                console.error("Error filtering by category:", error);
            }
        });
    });
});
    </script>
</x-app-layout>
