<x-app-layout>
    <div class="d-flex align-items-center justify-content-center min-vh-100">
        <div class="w-100" style="max-width: 600px;">
            <div class="card mt-4 p-4">
                <h2 class="text-center mb-4">แก้ไขข้อมูลส่วนตัว</h2>

                @if (session('success'))
                    <div class="mt-2 text-lg leading-8 text-gray-600 text-center mb-4">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('myprofilefinace.updatefinace') }}" id="update-form">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">ชื่อ</label>
                        <input type="text" id="name" name="name" value="{{ $user->name }}" required
                            autocomplete="given-name" class="form-control" placeholder="ชื่อ">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">อีเมล</label>
                        <input type="email" id="email" name="email" value="{{ $user->email }}" required
                            autocomplete="email" class="form-control" placeholder="อีเมล">
                    </div>
                    <div class="mb-3">
                        <label for="phone_number" class="form-label fw-semibold">ที่อยู่</label>
                        <input type="text" id="address" name="address" value="{{ $user->address }}" required
                            autocomplete="given-name" class="form-control" placeholder="ที่อยู๋">
                    </div>
                    <div class="mb-3">
                        <label for="phone_number" class="form-label fw-semibold">เบอร์มือถือ</label>
                        <input type="text" id="phone_number" name="phone_number" value="{{ $user->phone_number }}"
                            required autocomplete="given-name" class="form-control" placeholder="เบอร์มือถือ"
                            maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold">รหัสผ่านใหม่ (ถ้าต้องการเปลี่ยน)</label>
                        <input type="password" id="password" name="password" autocomplete="new-password"
                            class="form-control" placeholder="รหัสผ่านใหม่">
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label fw-semibold">ยืนยันรหัสผ่านใหม่</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            autocomplete="new-password" class="form-control" placeholder="ยืนยันรหัสผ่านใหม่">
                    </div>
                    <div class="mt-4 text-center">
                        <button type="submit" class="btn btn-primary" style="width: 10rem;"
                            id="update-button">อัพเดต</button>
                        <a href="{{ route('myprofilefinace.showfinace') }}" class="btn btn-success"
                            id="back-button">กลับหน้าข้อมูลส่วนตัวของฉัน</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // SweetAlert2 for Update Button
        document.getElementById('update-button').addEventListener('click', function(event) {
            event.preventDefault();
            Swal.fire({
                title: 'ยืนยันการอัพเดต',
                text: 'คุณต้องการอัพเดตข้อมูลส่วนตัวใช่หรือไม่?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่, อัพเดต!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('update-form').submit();
                }
            });
        });

        // SweetAlert2 for Back Button
        document.getElementById('back-button').addEventListener('click', function(event) {
            event.preventDefault();
            Swal.fire({
                title: 'กลับไปยังหน้าข้อมูลส่วนตัว',
                text: 'คุณแน่ใจหรือไม่ว่าต้องการกลับไปยังหน้าข้อมูลส่วนตัวของคุณ?',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่, กลับไป!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route('myprofilefinace.showfinace') }}';
                }
            });
        });
    </script>
</x-app-layout>
