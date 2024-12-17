<x-app-layout>
    <div class="card mt-4">
        <div class="container mt-5">
            <h1>รายการคำสั่งซื้อ</h1>

            <form id="search-form" class="mb-4">
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <input type="text" name="search" id="search" class="form-control"
                            placeholder="ค้นหาชื่อลูกค้า">
                    </div>
                    <div class="col-md-3 mb-2">
                        <select name="status" id="status" class="form-control">
                            <option value="all">สถานะทั้งหมด</option>
                            <option value="Pending">รอดำเนินการ</option>
                            <option value="Processing">กำลังดำเนินการ</option>
                            <option value="Shipped">จัดส่งแล้ว</option>
                            <option value="Rejected">ปฏิเสธการดำเนินการ</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <button type="submit" class="btn btn-primary w-100">ค้นหา</button>
                    </div>
                </div>
            </form>

            <div id="order-table">
                @include('Shippingreport.order_table')
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#search-form').on('submit', function(e) {
                    e.preventDefault();
                    fetchOrders(1);
                });

                $('#search, #status, #payment_status').on('change', function() {
                    fetchOrders(1);
                });

                $(document).on('click', '.pagination a', function(e) {
                    e.preventDefault();
                    var page = $(this).attr('href').split('page=')[1];
                    fetchOrders(page);
                });

                function fetchOrders(page) {
                    $.ajax({
                        url: '{{ route('Shippingreport.history') }}?page=' + page,
                        method: 'GET',
                        data: {
                            search: $('#search').val(),
                            status: $('#status').val(),
                            payment_status: $('#payment_status').val()
                        },
                        success: function(response) {
                            $('#order-table').html(response.html);
                            $('.pagination-container').html(response.pagination);
                            window.history.pushState("", "", '{{ route('Shippingreport.history') }}?page=' +
                                page);
                        },
                        error: function(xhr) {
                            console.log('Error:', xhr);
                        }
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
