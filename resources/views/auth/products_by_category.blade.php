@extends('layoutsauth.head')

@section('title', 'แบรนสินค้า')

@section('content')
    <div class="container mt-1">

        <div id="categoryCarousel" class="carousel slide mt-4" data-ride="carousel">
            <h2 class="mb-4">หมวดหมู่รองเท้า</h2>
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

            <!-- Section for displaying products of selected category -->
            <div class="container mt-4">
                <h2 class="mb-4">สินค้าในหมวดหมู่: {{ $selectedCategory->categories_name }}</h2>
                <div class="row">
                    @foreach ($products as $product)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <img src="{{ asset('storage/' . $product->pd_image) }}" class="card-img-top"
                                    alt="{{ $product->pd_name }}" style="height: 200px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $product->pd_name }}</h5>
                                    <p class="card-text">{{ Str::limit($product->pd_detail, 20) }}</p>
                                    <p class="card-text"><strong>ราคา: {{ number_format($product->pd_price, 2) }}
                                            บาท</strong></p>
                                    <a href="{{ route('auth.show', $product->id) }}"
                                        class="btn btn-primary">ดูรายละเอียด</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endsection

    <style>
        #productCarousel .carousel-inner {
            height: 300px;
        }

        #productCarousel .carousel-item img {
            width: 100%;
            height: 300px;
            object-fit: contain;
            border: 2px solid #ddd;
            border-radius: 5px;
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
