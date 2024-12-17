<x-app-layout>
    <div class="container mt-5">
        <h2 class="text-center py-2">Edit Information</h2>
        <form id="editpdForm" method="POST" action="{{ route('myproduct.update', $product->id) }}"
            enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="pd_name">ชื่อสินค้า</label>
                <input type="text" name="pd_name" id="pd_name" class="form-control" value="{{ $product->pd_name }}">
                @error('pd_name')
                    <div class="my-2">
                        <span class="text-danger">{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="pd_color">รายละเอียดสี</label>
                <textarea name="pd_color" id="pd_color" cols="30" rows="5" class="form-control">{{ $product->pd_color }}</textarea>
                @error('pd_color')
                    <div class="my-2">
                        <span class="text-danger">{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="pd_detail">รายละเอียดสินค้า</label>
                <textarea name="pd_detail" id="pd_detail" cols="30" rows="5" class="form-control">{{ $product->pd_detail }}</textarea>
                @error('pd_detail')
                    <div class="my-2">
                        <span class="text-danger">{{ $message }}</span>
                    </div>
                @enderror
            </div>
            <div class="form-group">
                <label for="pd_price">ราคา</label>
                <input type="text" name="pd_price" id="pd_price" class="form-control"
                    value="{{ $product->pd_price }}">
                @error('pd_price')
                    <div class="my-2">
                        <span class="text-danger">{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="pd_stock">จำนวนสินค้าคงเหลือ</label>
                <input type="text" name="pd_stock" id="pd_stock" class="form-control"
                    value="{{ $product->pd_stock }}">
                @error('pd_stock')
                    <div class="my-2">
                        <span class="text-danger">{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="category_id">แบรน</label>
                <select name="category_id" id="category_id" class="form-control">
                    <option value=""></option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ $product->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->categories_name }}
                        </option>
                    @endforeach
                </select>
                <div class="my-2 text-danger" id="error_category_id"></div>
            </div>

            <div class="form-group">
                <label for="pd_image">รูปสินค้า</label>
                <input type="file" name="pd_image" id="pd_image" class="form-control">
                @if ($product->pd_image)
                    <div class="my-2">
                        <img src="{{ asset('storage/' . $product->pd_image) }}" alt="{{ $product->pd_name }}"
                            class="img-fluid" width="100%">
                    </div>
                @endif
                @error('pd_image')
                    <div class="my-2">
                        <span class="text-danger">{{ $message }}</span>
                    </div>
                @enderror
            </div>
            <div class="form-group">
                <label for="pd_image_1">รูปตัวอย่างสินค้า1</label>
                <input type="file" name="pd_image_1" id="pd_image_1" class="form-control">
                @if ($product->pd_image_1)
                    <div class="my-2">
                        <img src="{{ asset('storage/' . $product->pd_image_1) }}" alt="pd_image_1"
                            class="img-fluid" width="100%">
                    </div>
                @endif
                @error('pd_image_1')
                    <div class="my-2">
                        <span class="text-danger">{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="pd_image_2">รูปตัวอย่างสินค้า2</label>
                <input type="file" name="pd_image_2" id="pd_image_2" class="form-control">
                @if ($product->pd_image_2)
                    <div class="my-2">
                        <img src="{{ asset('storage/' . $product->pd_image_2) }}" alt="pd_image_2"
                            class="img-fluid" width="100%">
                    </div>
                @endif
                @error('pd_image_2')
                    <div class="my-2">
                        <span class="text-danger">{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="pd_image_3">รูปตัวอย่างสินค้า3</label>
                <input type="file" name="pd_image_3" id="pd_image_3" class="form-control">
                @if ($product->pd_image_3)
                    <div class="my-2">
                        <img src="{{ asset('storage/' . $product->pd_image_3) }}" alt="pd_image_3"
                            class="img-fluid" width="100%">
                    </div>
                @endif
                @error('pd_image_3')
                    <div class="my-2">
                        <span class="text-danger">{{ $message }}</span>
                    </div>
                @enderror
            </div>
            <div class="d-flex justify-content-between">
                <input type="submit" id="saveButton" value="บันทึก" class="btn btn-primary my-3">
                <button id="homeButton" class="btn btn-success my-3">กลับหน้าหลัก</button>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            $('#saveButton').on('click', function(event) {
                event.preventDefault();

                Swal.fire({
                    title: "คุณต้องการแก้ไขข้อมูลใช่หรือไม่?",
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: "บันทึก",
                    cancelButtonText: 'ยกเลิก',
                    denyButtonText: `ไม่บันทึก`
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#editpdForm').off('submit').submit();
                    } else if (result.isDenied) {
                        Swal.fire("คุณไม่ได้แก้ไขข้อมูลสินค้า", "", "info");
                    }
                });
            });

            $('#homeButton').on('click', function(event) {
                event.preventDefault();

                Swal.fire({
                    title: "คุณแน่ใจหรือไม่?",
                    text: "คุณจะกลับไปหน้าแรก ใช่ไหรือไม่?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    cancelButtonText: 'ยกเลิก',
                    confirmButtonText: "ใช่"
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: "หน้าหลัก!",
                            text: "กลับหน้าหลัก",
                            icon: "success"
                        }).then(() => {
                            window.location.href = "{{ route('myproduct.home') }}";
                        });
                    }
                });
            });
        });
    </script>
</x-app-layout>
