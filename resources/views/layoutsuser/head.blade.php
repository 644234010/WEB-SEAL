<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;700&display=swap" rel="stylesheet">

</head>

<body>
    <div class="d-flex flex-column vh-100">
        <header>
            <nav class="navbar navbar-expand-lg" style="background-color: #3BA5B2;">
                <div class="container-fluid">
                    <a class="navbar-brand text-white" href="/userhome/products">SHOSE SALE</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                        <ul class="navbar-nav mx-auto">
                            @unless (request()->routeIs('cart.index') ||
                                    request()->routeIs('order.history') ||
                                    request()->routeIs('order.details') ||
                                    request()->routeIs('order.create') ||
                                    request()->routeIs('order.success')||
                                    request()->routeIs('game'))
                                <form class="" role="search" method="POST" action="{{ route('userhome.search') }}"
                                    onsubmit="return false;">
                                    @csrf
                                    <div class="input-group position-relative">

                                        <div style="position: relative;">
                                            <input type="text" id="search-input" class="search-bar"
                                                placeholder="ค้นหาสินค้า...">
                                            <ul id="search-suggestions" class="list-group" style="display: none;"></ul>
                                        </div>
                                    </div>
                                </form>
                            @endunless
                        </ul>
                        <div class="dropdown ms-auto">
                            <a href="#" class="brand-link text-white d-flex align-items-center "
                                id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="user-icon me-2" id="user-icon"></span>
                                <i class="bi bi-list ms-2"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                                <li><a class="dropdown-item" href="/myprofileuser">{{ Auth::user()->name }}</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="/about">เกี่ยวกับเรา</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item"
                                        href="{{ route('order.history') }}">ประวัติการสั่งซื้อและสถานะ</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item"
                                        href="/game">มินิเกม</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" id="logout-form">
                                        @csrf
                                        <button type="submit" class="dropdown-item"
                                            onclick="confirmLogout(event)">ออกจากระบบ</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
        </header>

        <main class="flex-grow-1 container mt-5">
            @yield('content')
        </main>

        <footer class="text-white text-center py-3" style="background-color: #3BA5B2;">
            @include('layoutsuser.footer')
        </footer>
    </div>

    <script>
        function confirmLogout(event) {
            event.preventDefault();
            Swal.fire({
                title: 'คุณแน่ใจหรือไม่?',
                text: "คุณต้องการออกจากระบบหรือไม่?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่, ออกจากระบบ!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            var userName = "{{ Auth::user()->name }}";
            var firstInitial = userName.charAt(0).toUpperCase();
            var userIconElement = document.getElementById('user-icon');
            userIconElement.textContent = firstInitial;
            userIconElement.style.display = 'inline-block';
            userIconElement.style.width = '30px';
            userIconElement.style.height = '30px';
            userIconElement.style.backgroundColor = '#666600';
            userIconElement.style.color = '#ffffff';
            userIconElement.style.borderRadius = '50%';
            userIconElement.style.textAlign = 'center';
            userIconElement.style.lineHeight = '30px';
            userIconElement.style.fontSize = '1rem';
        });

        $(document).ready(function() {
            $('#search-input').on('keyup', function() {
                let query = $(this).val();

                if (query.length > 0) {
                    $.ajax({
                        url: "{{ route('userhome.autocomplete') }}",
                        method: "GET",
                        data: {
                            query: query
                        },
                        success: function(response) {
                            $('#search-suggestions').empty().show();
                            if (response.suggestions.length > 0) {
                                $.each(response.suggestions, function(index, suggestion) {
                                    // Use the correct key 'pd_name' instead of 'name'
                                    $('#search-suggestions').append(
                                        `<li class="list-group-item" data-id="${suggestion.id}">${suggestion.pd_name}</li>`
                                    );
                                });
                            } else {
                                $('#search-suggestions').append(
                                    `<li class="list-group-item">ไม่พบผลลัพธ์</li>`);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('An error occurred:', error);
                        }
                    });
                } else {
                    $('#search-suggestions').hide();
                }
            });


            $(document).on('click', '.list-group-item', function() {
                let productId = $(this).data('id');
                if (productId) {
                    // Change URL to match the route definition
                    window.location.href = '/userhome/products/' + productId;
                }
            });

        });
    </script>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
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
    </style>
</body>

</html>
