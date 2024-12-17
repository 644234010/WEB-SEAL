@extends('layoutsauth.head')

@section('title', 'หน้าหลัก')

@section('content')

    <style>
        /* กรอบด้านบน */
        .top-bar {
            background-color: #1d1d1d;
            padding: 5px 0;
            font-size: 0.9rem;
            color: white;
        }

        .top-bar a {
            color: white;
            text-decoration: none;
            margin-right: 15px;
        }

        .top-bar a:hover {
            text-decoration: underline;
        }

        /* กรอบด้านล่าง (Navbar หลัก) */
        .main-navbar {
            background-color: #343a40;
            padding: 10px 0;
        }

        .navbar-dark .navbar-nav .nav-link {
            color: white;
        }

        .navbar-dark .navbar-nav .nav-link:hover {
            color: orange;
        }

        .navbar-dark .navbar-nav .nav-link.active {
            color: orange;
        }

        /* โลโก้ */
        .navbar-brand img {
            width: 40px;
            height: 40px;
        }

        /* ไอคอนในกรอบล่าง */
        .search-cart-icons {
            color: white;
            font-size: 1.2rem;
        }

        .cart-icon {
            position: relative;
        }

        .cart-icon .badge {
            position: absolute;
            top: -5px;
            right: -10px;
            background-color: orange;
            color: white;
            border-radius: 50%;
            padding: 2px 5px;
            font-size: 0.8rem;
        }

        .h6 {
            font-size: 100rem
        }

        #productCarousel {
            height: auto;
            /* Adjust this depending on how much space you want the carousel to take */
            max-height: 600px;
            /* Adjust the max height to your needs */
            overflow: hidden;
        }

        #productCarousel .carousel-inner {
            height: 100%;
        }

        #productCarousel .carousel-item img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            /* This ensures the image fits without being cut off */
            max-height: 600px;
            /* Ensure the image doesn't overflow */
        }

        .card-header .h1,
        .login-box-msg,
        #forgot-password-link,
        #register-link {
            color: white;

        }

        label {
            color: white;
            /* text-shadow: 2px 2px 0px purple; */

        }

        header {
            width: 100%;
        }

        .navbar-brand,
        .nav-link,
        .dropdown-toggle {
            color: white !important;
        }

        .user-icon {
            margin-right: 5px;
        }

        /* ปรับกล่องค้นหาให้ดูดีขึ้น */
        .search-bar {
            width: 1000px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 20px;
            /* ขอบกล่องโค้งมน */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            /* เพิ่มเงาที่ขอบกล่อง */
            transition: box-shadow 0.3s ease;
            /* เพิ่ม transition เวลาโฟกัส */
        }

        .search-bar:focus {
            outline: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            /* เปลี่ยนเงาเมื่อโฟกัส */
            border: 1px solid #3BA5B2;
            /* เปลี่ยนสีขอบกล่องเมื่อโฟกัส */
        }

        .input-group {
            width: 100%;
        }

        /* ปรับแต่งกล่องคำแนะนำ */
        #search-suggestions {
            position: absolute;
            background-color: white;
            border: 1px solid #ddd;
            z-index: 1000;
            width: 100%;
            max-height: 300px;
            overflow-y: auto;
            margin-top: 5px;
            border-radius: 10px;
            /* ทำให้ขอบกล่องคำแนะนำโค้งมน */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            /* เพิ่มเงาให้กล่องคำแนะนำ */
        }

        /* แต่งรายการคำแนะนำ */
        #search-suggestions .list-group-item {
            cursor: pointer;
            padding: 10px;
            border: none;
            border-bottom: 1px solid #ddd;
            /* เพิ่มเส้นแบ่งระหว่างรายการ */
            transition: background-color 0.2s ease;
            /* เพิ่ม transition เมื่อ hover */
        }

        /* เปลี่ยนสีพื้นหลังเมื่อ hover */
        #search-suggestions .list-group-item:hover {
            background-color: #f0f0f0;
        }

        /* ลบเส้นแบ่งสำหรับรายการสุดท้าย */
        #search-suggestions .list-group-item:last-child {
            border-bottom: none;
        }

        #categoryCarousel .carousel-item img.category-img {
            height: 150px;
            object-fit: contain;
            border: 2px solid #ddd;
            border-radius: 5px;
        }

        .card {
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card-img-top {
            height: 200px;
            object-fit: contain;
        }

        .card-title {
            font-size: 1.25rem;
            margin-bottom: 0.75rem;
        }

        .card-text {
            margin-bottom: 1rem;
        }

        .category-row img {
            height: 80px;
            object-fit: contain;
        }

        .category-row p {
            margin-top: 10px;
            font-size: 14px;
        }

        .carousel-control-prev-icon.black-icon,
        .carousel-control-next-icon.black-icon {
            background-color: black;
            border-radius: 50%;
            width: 40px;
            height: 40px;
        }
    </style>

    <body>
    </body>

    <div class="row justify-content-center">
        <div class="col-12">
            <div id="productCarousel" class="carousel slide" data-ride="carousel" data-interval="3000">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img class="d-block w-100 border rounded" src="{{ asset('img/860.jpeg') }}" alt="รองเท้า">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100 border rounded" src="{{ asset('img/04.png') }}" alt="รองเท้า">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100 border rounded" src="{{ asset('img/03.jpg') }}" alt="รองเท้า">
                    </div>
                </div>
                <a class="carousel-control-prev" href="#productCarousel" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#productCarousel" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
            <div id="categoryCarousel" class="carousel slide mt-4" data-ride="carousel">
                <h2 class="mb-4">แบรนรองเท้า</h2>
                <div class="carousel-inner">
                    @foreach ($categories->chunk(3) as $index => $chunk)
                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                            <div class="row text-center">
                                @foreach ($chunk as $category)
                                    <div class="col-4">
                                        <a href="{{ route('auth.productsByCategory', $category->id) }}"
                                            class="category-link">

                                            @php
                                                $imagePath = '';

                                                switch (strtolower($category->categories_name)) {
                                                    case 'converse':
                                                        $imagePath = '/img/CV_Logo.jpg';
                                                        break;
                                                    case 'reebok':
                                                        $imagePath = '/img/Reebok.png';
                                                        break;
                                                    case 'nike':
                                                        $imagePath = '/img/nike.jpg';
                                                        break;
                                                    case 'adidas':
                                                        $imagePath = '/img/Adidas_Logo.png';
                                                        break;
                                                    case 'new balance':
                                                        $imagePath = '/img/New_Balance-Logo.png';
                                                        break;
                                                    case 'onitsuka tiger':
                                                        $imagePath = '/img/OnitsukaTiger.jpg';
                                                        break;
                                                    case 'keds':
                                                        $imagePath = '/img/Keds_logo.jpg';
                                                        break;
                                                    default:
                                                        $imagePath = '/img/shoe.png';
                                                        break;
                                                }
                                            @endphp
                                            <img src="{{ asset($imagePath) }}" alt="{{ $category->categories_name }}"
                                                class="fa-3x mt-3 category-img">
                                            <p class="mt-2">{{ $category->categories_name }}</p>

                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <a class="carousel-control-prev" href="#categoryCarousel" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon black-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#categoryCarousel" role="button" data-slide="next">
                        <span class="carousel-control-next-icon black-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>


                <div class="container mt-4">
                    <h2 class="mb-4">สินค้าแนะนำ</h2>
                    <div class="row">
                        @foreach ($recommendedProducts as $product)
                            <div class="col-md-4 mb-4">
                                <div class="card h-100">
                                    <img src="{{ asset('storage/' . $product->pd_image) }}" class="card-img-top"
                                        alt="{{ $product->pd_name }}" style="height: 200px; object-fit: cover;">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $product->pd_name }}</h5>
                                        <p class="card-text">{{ Str::limit($product->pd_detail, 20) }}</p>
                                        <p class="card-text"><strong>ราคา: {{ number_format($product->pd_price, 2) }}
                                                บาท</strong>
                                        </p>
                                        <a href="{{ route('auth.show', $product->id) }}"
                                            class="btn btn-primary">ดูรายละเอียด</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <h2 class="display-4 font-weight-bold text-primary mt-4">รายการสินค้า</h2>
                <hr class="my-4">
                <div class="table-responsive" id="products-list">
                    @include('auth.prohome', ['products' => $products])
                </div>
            </div>
        </div>

        <div class="modal fade @if($errors->any()) show @endif" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true" @if($errors->any()) style="display: block;" @endif>
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="background-image: url('{{ asset('img/860.jpeg') }}');">
                    <form method="POST" action="{{ route('login') }}" id="login-form">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="loginModalLabel" style="color: white">เข้าสู่ระบบ</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body d-flex justify-content-center align-items-center">
                            <div class="login-box">
                                <div class="card-header text-center">
                                    <a href="#" class="h1">SHOSE SALE</a>
                                </div>
                                <div class="card-body">
                                    <p class="login-box-msg">กรุณาป้อนอีเมล์และรหัสผ่านเพื่อเข้าสู่ระบบ</p>
                             

                                    <x-auth-session-status :status="$errors->first('failure')" />

                                    <x-forms.input type="email" label="อีเมล์" name="email"
                                        placeholder="กรุณาป้อนอีเมล์" />
                                    <x-forms.input type="password" label="รหัสผ่าน" name="password"
                                        placeholder="กรุณาป้อนรหัสผ่าน" />

                                    <div class="row">
                                        <div class="col-8">
                                            <div class="icheck-primary">
                                                <input type="checkbox" id="remember" name="remember">
                                                <label for="remember" style="color: white">จดจำฉัน</label>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <button type="submit" class="btn btn-primary btn-block"
                                                id="login-button">เข้าสู่ระบบ</button>
                                        </div>
                                    </div>

                                    <div class="social-auth-links text-center mt-2 mb-3">
                                        <a href="{{ url('auth/google') }}" class="btn btn-block btn-danger"
                                            id="google-login-button">
                                            <i class="fab fa-google-plus mr-2"></i> เข้าสู่ระบบผ่าน Google+
                                        </a>
                                    </div>

                                    <p class="mb-1">
                                        <a href="{{ route('password.request') }}"
                                            id="forgot-password-link">ลืมรหัสผ่าน</a>
                                    </p>
                                    <p class="mb-0">
                                        <a href="{{ route('register') }}" class="text-center"
                                            id="register-link">สมัครสมาชิก</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <footer class="text-white text-center py-3" style="background-color: #3BA5B2;">
            @include('layoutsauth.footer')
        </footer>
    </div>
    </div>


    <!-- JavaScript -->
    <script>
        document.getElementById('login-button').addEventListener('click', function(event) {
            event.preventDefault();
            Swal.fire({
                title: 'เข้าสู่ระบบ',
                text: 'กำลังตรวจสอบข้อมูล...',
                icon: 'info',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                    document.getElementById('login-form').submit();
                }
            });
        });
        document.getElementById('google-login-button').addEventListener('click', function(event) {
            event.preventDefault();
            Swal.fire({
                title: 'เข้าสู่ระบบผ่าน Google+',
                text: 'กำลังเปลี่ยนเส้นทาง...',
                icon: 'info',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                    window.location.href = "{{ url('auth/google') }}";
                }
            });
        });

        document.getElementById('forgot-password-link').addEventListener('click', function(event) {
            event.preventDefault();
            Swal.fire({
                title: 'ลืมรหัสผ่าน',
                text: 'กำลังเปลี่ยนเส้นทาง...',
                icon: 'info',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                    window.location.href = "{{ route('password.request') }}";
                }
            });
        });

        document.getElementById('register-link').addEventListener('click', function(event) {
            event.preventDefault();
            Swal.fire({
                title: 'สมัครสมาชิก',
                text: 'กำลังเปลี่ยนเส้นทาง...',
                icon: 'info',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                    window.location.href = "{{ route('register') }}";
                }
            });
        });
    </script>

@endsection
