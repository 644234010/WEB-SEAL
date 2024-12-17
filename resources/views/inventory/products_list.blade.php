<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th scope="col">รูปภาพ</th>
                <th scope="col">ชื่อ</th>
                <th scope="col">รายละเอียดสีรองเท้า</th>
                <th scope="col">รายละเอียดสินค้า</th>
                <th scope="col">ราคาสินค้า</th>
                <th scope="col">แบรน</th>
                <th scope="col">จำนวนสินค้าคงเหลือ</th>
                <th scope="col">แก้ไขรายการสินค้า</th>
                <th scope="col">ลบรายการสินค้า</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($productshoes as $item)
                <tr>
                    <td>
                        @if ($item->pd_image)
                            <img src="{{ asset('storage/' . $item->pd_image) }}" alt="{{ $item->pd_name }}"
                                class="img-fluid" width="200" height="auto">
                        @endif
                    </td>
                    <td>{{ $item->pd_name }}</td>
                    <td>{{ $item->pd_color }}</td>
                    <td>{{ Str::limit($item->pd_detail, 50) }}</td>
                    <td>{{ number_format($item->pd_price) }}</td>
                    <td>{{ Str::limit($item->categories_name, 100) }}</td>
                    <td>{{ number_format($item->pd_stock) }}</td>
                    <td>
                        <a href="{{ route('inventory.edit', $item->id) }}" class="btn btn-warning btn-sm"
                            onclick="confirmEdit(event, '{{ route('inventory.edit', $item->id) }}')">แก้ไขรายการสินค้า</a>
                    </td>
                    <td>
                        <form action="{{ route('inventory.delete', $item->id) }}" method="POST" class="delete-form">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="confirmDelete(event, '{{ $item->pd_name }}')">ลบรายการสินค้า</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        @if ($productshoes->lastPage() > 1)
            <ul class="pagination">
                @if ($productshoes->onFirstPage())
                    <li class="page-item disabled"><span class="page-link">ย้อนกลับ</span></li>
                @else
                    <li class="page-item"><a class="page-link"
                            href="{{ $productshoes->previousPageUrl() }}">ย้อนกลับ</a></li>
                @endif

                @for ($i = 1; $i <= $productshoes->lastPage(); $i++)
                    @if (
                        $i == 1 ||
                            $i == $productshoes->lastPage() ||
                            ($i >= $productshoes->currentPage() - 1 && $i <= $productshoes->currentPage() + 1))
                        @if ($i == $productshoes->currentPage())
                            <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link"
                                    href="{{ $productshoes->url($i) }}">{{ $i }}</a></li>
                        @endif
                    @elseif ($i == $productshoes->currentPage() - 2 || $i == $productshoes->currentPage() + 2)
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                    @endif
                @endfor

                @if ($productshoes->hasMorePages())
                    <li class="page-item"><a class="page-link" href="{{ $productshoes->nextPageUrl() }}">ถัดไป</a></li>
                @else
                    <li class="page-item disabled"><span class="page-link">ถัดไป</span></li>
                @endif
            </ul>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // ฟังก์ชัน SweetAlert2 สำหรับปุ่ม "ลบรายการสินค้า"
    function confirmDelete(event, name) {
        event.preventDefault();
        Swal.fire({
            title: 'คุณแน่ใจหรือไม่?',
            text: `คุณต้องการลบ "${name}" หรือไม่? คุณจะไม่สามารถเปลี่ยนกลับสิ่งนี้ได้!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'ยกเลิก',
            confirmButtonText: 'ใช่, ลบ!'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'ลบรายการสินค้า!',
                    text: 'รายการสินค้าได้ถูกลบแล้ว',
                    icon: 'success'
                }).then(() => {
                    event.target.closest('form').submit();
                });
            }
        });
    }

    // ฟังก์ชัน SweetAlert2 สำหรับปุ่ม "แก้ไขรายการสินค้า"
    function confirmEdit(event, url) {
        event.preventDefault();
        Swal.fire({
            title: 'คุณแน่ใจหรือไม่?',
            text: `คุณต้องการแก้ไขรายการนี้หรือไม่?`,
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'ยกเลิก',
            confirmButtonText: 'ใช่, แก้ไข!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    }
</script>
