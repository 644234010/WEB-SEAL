<x-guest-layout class="login-page">
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <a href="#" class="h1">กู้คืนรหัสผ่าน</a>
        </div>
        <div class="card-body">
            <p class="login-box-msg">กรุณากรอกช่องด้านล่างเพื่อกู้คืนรหัสผ่าน</p>
            <form action="{{ route('password.email') }}" method="post" id="password-recovery-form">
                @csrf
                <x-forms.input type="email" label="อีเมลล์" name="email" placeholder="กรุณาป้อนอีเมล์" />
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block" id="request-password-button">ขอรหัสผ่านใหม่</button>
                    </div>
                </div>
            </form>
            <p class="mt-3 mb-1">
                <a href="{{ route('login') }}" id="login-link">เข้าสู่ระบบ</a>
            </p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('request-password-button').addEventListener('click', function(event) {
            event.preventDefault();
            Swal.fire({
                title: 'ขอรหัสผ่านใหม่',
                text: 'กำลังตรวจสอบข้อมูล...',
                icon: 'info',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                    document.getElementById('password-recovery-form').submit();
                }
            });
        });

        document.getElementById('login-link').addEventListener('click', function(event) {
            event.preventDefault();
            Swal.fire({
                title: 'เข้าสู่ระบบ',
                text: 'กำลังเปลี่ยนเส้นทาง...',
                icon: 'info',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                    window.location.href = "{{ route('login') }}";
                }
            });
        });
    </script>
</x-guest-layout>
