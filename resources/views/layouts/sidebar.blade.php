<style>
    .sidebar-footer {
        position: absolute;
        bottom: 0;
        width: 100%;
        padding: 20px;
    }

    .btn-logout {
        width: 100%;
        white-space: nowrap;
    }

    @media (max-width: 576px) {
        .btn-logout {
            font-size: 0.875rem;
            /* ขนาดตัวอักษรเมื่อย่อหน้า */
            padding: 0.375rem 0.75rem;
            /* การกำหนด Padding เมื่อย่อหน้า */
        }
    }
</style>




@php
    define('ADMIN_TYPE', 1);
    define('DEFAULT_TYPE', 0);
    define('FINANCE_TYPE', 2);
    define('SHIPPING_TYPE', 3);
    define('INVENTORY_TYPE', 4);
    $userType = Auth::user()->type;
@endphp

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    @if ($userType == ADMIN_TYPE)
    <a href="{{ route('myprofile.show') }}" class="brand-link" id="profile-link">
        <span class="user-icon" id="user-icon"></span>
        <span class="brand-text font-weight-light">{{ Auth::user()->name }}</span>
    </a>
    @endif

    @if ($userType == SHIPPING_TYPE)
    <a href="{{ route('myprofileshiping.showshiping') }}" class="brand-link" id="profileshipping-link">
        <span class="user-icon" id="user-icon"></span>
        <span class="brand-text font-weight-light">{{ Auth::user()->name }}</span>
    </a>
    @endif

    @if ($userType == INVENTORY_TYPE)
    <a href="{{ route('myprofileinventory.showinventory') }}" class="brand-link" id="profileinventory-link">
        <span class="user-icon" id="user-icon"></span>
        <span class="brand-text font-weight-light">{{ Auth::user()->name }}</span>
    </a>
    @endif

    @if ($userType == FINANCE_TYPE)
    <a href="{{ route('myprofilefinace.showfinace') }}" class="brand-link" id="profilefinance-link">
        <span class="user-icon" id="user-icon"></span>
        <span class="brand-text font-weight-light">{{ Auth::user()->name }}</span>
    </a>
    @endif

    <p>
    <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-sidebar" id="search-button">
                    <i class="fas fa-search fa-fw"></i>
                </button>
            </div>
        </div>
    </div>
    </p>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">



                @if ($userType == ADMIN_TYPE)
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link" id="dashboard-link">
                            <i class="bi bi-speedometer"></i>
                            <p>
                                แดชบอร์ด
                            </p>
                        </a>
                    </li>
                @endif
                @if ($userType == FINANCE_TYPE)
                    <li class="nav-item">
                        <a href="{{ route('finance') }}" class="nav-link" id="finance-link">
                            <i class="bi bi-speedometer"></i>
                            <p>
                                แดชบอร์ด
                            </p>
                        </a>
                    </li>
                @endif
                @if ($userType == SHIPPING_TYPE)
                    <li class="nav-item">
                        <a href="{{ route('shipping') }}" class="nav-link" id="shipping-link">
                            <i class="bi bi-speedometer"></i>
                            <p>
                                แดชบอร์ด
                            </p>
                        </a>
                    </li>
                @endif
                @if ($userType == INVENTORY_TYPE)
                    <li class="nav-item">
                        <a href="{{ route('inventory') }}" class="nav-link" id="inventory-link">
                            <i class="bi bi-speedometer"></i>
                            <p>
                                แดชบอร์ด
                            </p>
                        </a>
                    </li>
                @endif

                @if ($userType == ADMIN_TYPE)
                    <li class="nav-item">
                        <a href="{{ route('myproduct.home') }}" class="nav-link" id="products-link">
                            <i class="fab fa-product-hunt"></i>
                            <p>สินค้าทั้งหมด</p>
                        </a>
                    </li>
                @endif

                @if ($userType == INVENTORY_TYPE)
                    <li class="nav-item">
                        <a href="{{ route('inventory.home') }}" class="nav-link" id="inventoryproducts-link">
                            <i class="fab fa-product-hunt"></i>
                            <p>สินค้าทั้งหมด</p>
                        </a>
                    </li>
                @endif

                @if ($userType == ADMIN_TYPE)
                    <li class="nav-item">
                        <a href="/report/report-order" class="nav-link" id="order-status-link">
                            <i class="bi bi-shop"></i>
                            <p>จัดการสถานะรายการสั่งซื้อ</p>
                        </a>
                    </li>
                @endif
                @if ($userType == FINANCE_TYPE)
                    <li class="nav-item">
                        <a href="/finacereport/finacereport.history" class="nav-link"
                            id="orderFINANCE_TYPE-status-link">
                            <i class="bi bi-shop"></i>
                            <p>จัดการสถานะการเงิน</p>
                        </a>
                    </li>
                @endif
                @if ($userType == SHIPPING_TYPE)
                    <li class="nav-item">
                        <a href="/Shippingreport/Shippingreport.history" class="nav-link"
                            id="orderSHIPPING_TYPE-status-link">
                            <i class="bi bi-shop"></i>
                            <p>จัดการสถานะการจัดส่ง</p>
                        </a>
                    </li>
                @endif

                @if ($userType == ADMIN_TYPE)
                    <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="nav-icon fas fa-user-cog mr-2"></i>
                        <span>จัดการข้อมูล</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-white shadow animated--fade-in mt-1" aria-labelledby="navbarDropdown">
                        <h6 class="dropdown-header text-dark">เมนูการจัดการ</h6>
                        
                        <a class="dropdown-item d-flex align-items-center text-dark" href="{{ route('edituser.userall') }}" id="manage-users-link">
                            <i class="fas fa-users mr-2"></i>
                            <span>จัดการข้อมูลสมาชิก</span>
                        </a>
                        <a class="dropdown-item d-flex align-items-center text-dark" href="{{ route('editshiping.userall') }}" id="manage-shiping-link">
                            <i class="fas fa-shipping-fast mr-2"></i>
                            <span>จัดการข้อมูลพนักงานจัดส่ง</span>
                        </a>
                        <a class="dropdown-item d-flex align-items-center text-dark" href="{{ route('editinventory.userall') }}" id="manage-inventory-link">
                            <i class="fas fa-warehouse mr-2"></i>
                            <span>จัดการข้อมูลพนักงานคลัง</span>
                        </a>
                        <a class="dropdown-item d-flex align-items-center text-dark" href="{{ route('editfincereport.userall') }}" id="manage-fince-link">
                            <i class="fas fa-calculator mr-2"></i>
                            <span>จัดการข้อมูลพนักงานบัญชี</span>
                        </a>
                    </div>
                </li>
            @endif
            

            </ul>
        </nav>
    </div>

    <!-- Logout Form -->
    <div class="sidebar-footer">
        <form action="{{ route('logout') }}" method="POST" class="d-inline" id="logout-form">
            @csrf
            <button type="submit" class="btn btn-danger btn-block" id="logout-button">ออกจากระบบ</button>
        </form>
    </div>
</aside>

<script>
 
</script>

