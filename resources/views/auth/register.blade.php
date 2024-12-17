<x-guest-layout class="register-page" style="background-image: url('{{ asset('img/860.jpeg') }}');">
    <div class="register-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="#" class="h1">SHOSE SALE</a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">สมัครสมาชิก</p>

                <form action="{{ route('register') }}" method="post" id="register-form">
                    @csrf
                    <x-forms.input type="text" label="ชื่อ" name="name" placeholder="กรุณาป้อนชื่อ" />
                    <x-forms.input type="email" label="อีเมลล์" name="email" placeholder="กรุณาป้อนอีเมล์" />
                    <x-forms.input type="password" label="รหัสผ่าน" name="password" placeholder="กรุณาป้อนรหัสผ่าน" />
                    <x-forms.input type="password" label="กรุณากรอกรหัสผ่านใหม่อีกครั้ง" name="password_confirmation"
                        placeholder="กรุณาป้อนรหัสผ่านใหม่อีกครั้ง" />
                    <x-forms.input type="text" label="ที่อยู่" name="address" placeholder="กรุณาป้อนที่อยู่" />
                    <x-forms.input type="text" label="เบอร์มือถือ" name="phone_number"
                        placeholder="กรุณาป้อนเบอร์มือถือ" maxlength="10"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);" />

                    <div class="row">
                        <div class="col-5">
                            <button type="submit" class="btn btn-primary btn-block"
                                id="register-button">สมัครสมาชิก</button>
                        </div>
                    </div>
                </form>

                <div class="social-auth-links text-center">
                    <a href="{{ url('auth/google') }}" class="btn btn-block btn-danger" id="google-register-button">
                        <i class="fab fa-google-plus mr-2"></i>
                        สมัครโดยใช้ Google+
                    </a>
                </div>
                <a href="/" class="text-center" id="login-link">ฉันมีสมาชิกอยู่แล้ว</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('register-button').addEventListener('click', function(event) {
            event.preventDefault();
            Swal.fire({
                title: 'สมัครสมาชิก',
                text: 'กำลังตรวจสอบข้อมูล...',
                icon: 'info',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                    document.getElementById('register-form').submit();
                }
            });
        });

        document.getElementById('google-register-button').addEventListener('click', function(event) {
            event.preventDefault();
            Swal.fire({
                title: 'สมัครโดยใช้ Google+',
                text: 'กำลังเปลี่ยนเส้นทาง...',
                icon: 'info',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                    window.location.href = "{{ url('auth/google') }}";
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
                    window.location.href = "/";
                }
            });
        });
    </script>
</x-guest-layout>
