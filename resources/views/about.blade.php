@extends('layoutsuser.head')

@section('title', 'หน้าหลัก')

@section('content')
    <style>
        .about-section {
            padding: 60px 0;
        }

        .about-section h2 {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .about-section p {
            font-size: 1.1rem;
            line-height: 1.8;
        }

        .about-section img {
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .map-section {
            padding: 60px 0;
        }

        .map-container {
            height: 400px;
            width: 100%;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>

    <!-- About Us Section -->
    <div class="container about-section mt-5">
        <div class="row align-items-center">
            <div class="col-md-6 mb-4 mb-md-0">
                <h2>เกี่ยวกับเรา</h2>
                <p>บริษัทของเรามีประวัติการทำงานที่ยาวนานและมีความเชี่ยวชาญในด้านต่าง ๆ ซึ่งรวมถึงการพัฒนาเว็บ,
                    การออกแบบกราฟิก, และการให้คำปรึกษาทางธุรกิจ
                    เรามุ่งมั่นในการให้บริการที่ดีที่สุดแก่ลูกค้าของเราและการพัฒนาสังคมในทุกๆ ด้าน</p>
                <p>เป้าหมายของเราคือการสร้างความสัมพันธ์ที่ยาวนานกับลูกค้าและการทำงานร่วมกันเพื่อความสำเร็จในระยะยาว</p>
            </div>
            <div class="col-md-6">
                <img src="/img/01.png" class="img-fluid" alt="เกี่ยวกับเรา">
            </div>
        </div>
    </div>
@endsection