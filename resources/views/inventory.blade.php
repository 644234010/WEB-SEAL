<x-app-layout>
    <!-- Header Section -->
    <div class="content-header py-3 bg-light border-bottom">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0">Inventory Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right bg-transparent p-0 m-0">
                        <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active">Inventory</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Section -->
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info shadow-sm">
                    <div class="inner">
                        <h3>{{ $totalProducts }}</h3>
                        <p>จำนวนสินค้าทั้งหมด</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-cube fa-3x"></i>
                    </div>
                    <a href="#" class="small-box-footer text-white">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-success shadow-sm">
                    <div class="inner">
                        <h3>{{ $totalStock }}</h3>
                        <p>จำนวน Stock ทั้งหมด</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-warehouse fa-3x"></i>
                    </div>
                    <a href="#" class="small-box-footer text-white">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>

        <!-- Detailed Data Section -->
        <div class="row">
            <!-- Category Product Counts -->
            <div class="col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title">จำนวนสินค้าแต่ละหมวดหมู่</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>หมวดหมู่</th>
                                    <th>จำนวนสินค้า</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categoryProductCounts as $category)
                                    <tr>
                                        <td>{{ $category->categories_name }}</td>
                                        <td>{{ $category->count }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Low Stock Products -->
            <div class="col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-danger text-white">
                        <h3 class="card-title">สินค้าที่กำลังจะหมด Stock</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>สินค้า</th>
                                    <th>หมวดหมู่</th>
                                    <th>จำนวนคงเหลือ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($lowStockProducts as $product)
                                    <tr>
                                        <td>{{ $product->pd_name }}</td>
                                        <td>{{ $product->categories_name }}</td>
                                        <td>
                                            <span class="badge bg-warning">{{ $product->pd_stock }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
