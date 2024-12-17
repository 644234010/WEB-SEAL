<x-app-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white text-center">
                        <h2>ข้อมูลส่วนตัวของฉัน</h2>
                    </div>
                    <div class="card-body">
                        <p class="mb-3"><strong>ชื่อ:</strong> {{ $user->name }}</p>
                        <p class="mb-3"><strong>อีเมล:</strong> {{ $user->email }}</p>
                        <p class="mb-3"><strong>ที่อยู่:</strong> {{ $user->address }}</p>
                        <p class="mb-3"><strong>เบอร์มือถือ:</strong> {{ $user->phone_number }}</p>
                        <p class="mb-4"><strong>รหัสผ่าน:</strong>
                            {{ str_repeat('*', min(strlen($user->password), 10)) }}</p>
                        <div class="text-center">
                            <a href="{{ route('myprofileshiping.editshiping') }}" class="btn btn-primary"
                                id="edit-button">แก้ไขข้อมูล</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // SweetAlert2 for Edit Button
        document.getElementById('edit-button').addEventListener('click', function(event) {
            event.preventDefault();
            Swal.fire({
                title: 'แก้ไขข้อมูล',
                text: 'คุณต้องการแก้ไขข้อมูลส่วนตัวของคุณหรือไม่?',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่, แก้ไข!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route('myprofileshiping.editshiping') }}';
                }
            });
        });
    </script>
</x-app-layout>
