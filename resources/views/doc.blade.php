<x-app-layout>
    <div class="container my-5">
        <h1 class="text-center bg-primary text-white py-4 rounded-lg shadow mb-5">รายงานสรุปยอดขาย (สินค้าทั้งหมด)</h1>

        <div class="d-flex flex-wrap justify-content-between align-items-center mt-4 mb-5">
            <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-lg mb-3">
                <i class="bi bi-arrow-left-circle"></i> กลับสู่หน้าแดชบอร์ด
            </a>
            <div class="d-flex flex-wrap">
                <div class="input-group mb-3 me-3" style="width: 300px;">
                    <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                    <input type="date" id="startDate" class="form-control" placeholder="วันที่เริ่มต้น">
                    <input type="date" id="endDate" class="form-control" placeholder="วันที่สิ้นสุด">
                </div>
                <div class="input-group mb-3 me-3" style="width: 200px;">
                    <select id="categoryFilter" class="form-select">
                        <option value="">ทุกหมวดหมู่</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->categories_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="input-group mb-3" style="width: 300px;">
                    <input type="text" id="searchName" class="form-control" placeholder="ค้นหาตามชื่อ">
                </div>
            </div>
        </div>

        <div class="row text-center mb-4">
            <div class="col-md-6 mb-3">
                <div class="card shadow border-0 h-100 bg-light">
                    <div class="card-body">
                        <h5 class="card-title text-primary">จำนวนรายการทั้งหมด</h5>
                        <p class="card-text display-4 fw-bold total-documents">{{ $totalDocuments }} รายการ</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card shadow border-0 h-100 bg-light">
                    <div class="card-body">
                        <h5 class="card-title text-success">ยอดขายทั้งหมด</h5>
                        <p class="card-text display-4 fw-bold total-amount">{{ number_format($totalAmount, 2) }} บาท</p>
                    </div>
                </div>
            </div>
        </div>

      

        <form id="downloadForm" action="{{ route('download') }}" method="POST">
            @csrf
            <input type="hidden" name="selected_orders" id="selectedOrdersInput">
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="format" class="form-label">เลือกรูปแบบไฟล์:</label>
                    <select name="format" id="format" class="form-select">
                        <option value="pdf">PDF</option>
                        <option value="excel">Excel</option>
                    </select>
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <button id="downloadSelected" type="submit" class="btn btn-primary btn-lg w-100">
                        <i class="bi bi-download"></i> ดาวน์โหลดเอกสารที่เลือก
                    </button>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" id="ordersTable">
                <thead class="table-dark">
                    <tr>
                        <th><input type="checkbox" id="selectAll"> เลือกทั้งหมด</th>
                        <th>เลขที่</th>
                        <th>วันที่สร้างเอกสาร</th>
                        <th>ชื่อผู้ใช้</th>
                        <th>ยอด</th>
                        <th>อีเมล</th>
                        <th>หมวดหมู่</th>
                        <th>สถานะการจัดส่ง</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td><input type="checkbox" name="selected_orders[]" value="{{ $order->id }}"></td>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->created_at }}</td>
                            <td>{{ $order->company }}</td>
                            <td>{{ number_format($order->total_amount, 2) }} บาท</td>
                            <td>{{ $order->email }}</td>
                            <td>{{ $order->category_name }}</td>
                            <td>
                                @switch($order->status)
                                    @case('Shipped')
                                        <span class="badge bg-success">จัดส่งแล้ว</span>
                                    @break

                                    @default
                                        <span class="badge bg-secondary">{{ $order->status }}</span>
                                @endswitch
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categoryFilter = document.getElementById('categoryFilter');
            const startDate = document.getElementById('startDate');
            const endDate = document.getElementById('endDate');
            const searchName = document.getElementById('searchName');
            const totalDocumentsElement = document.querySelector('.total-documents');
            const totalAmountElement = document.querySelector('.total-amount');
            const selectAllCheckbox = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('input[name="selected_orders[]"]');
            const downloadButton = document.getElementById('downloadSelected');
            const downloadForm = document.getElementById('downloadForm');
            let chart;

            function updateData() {
                const category = categoryFilter.value;
                const start = startDate.value;
                const end = endDate.value;
                const search = searchName.value;

                fetch(`/filter-orders?category=${category}&startDate=${start}&endDate=${end}&searchName=${search}`)
                    .then(response => response.json())
                    .then(data => {
                        updateTable(data.orders);
                        updateSummary(data.totalDocuments, data.totalAmount);
                        updateChart(data.brandOrders);
                        updateCheckboxes();
                    });
            }

            function updateTable(orders) {
                const tableBody = document.querySelector('#ordersTable tbody');
                tableBody.innerHTML = '';
                orders.forEach(order => {
                    const row = `
            <tr>
                <td><input type="checkbox" name="selected_orders[]" value="${order.id}" checked></td>
                <td>${order.id}</td>
                <td>${order.created_at}</td>
                <td>${order.company}</td>
                <td>${new Intl.NumberFormat('th-TH', { style: 'currency', currency: 'THB' }).format(order.total_amount)}</td>
                <td>${order.email}</td>
                <td>${order.category_name}</td>
                <td><span class="badge bg-${order.status === 'Shipped' ? 'success' : 'secondary'}">${order.status === 'Shipped' ? 'จัดส่งแล้ว' : order.status}</span></td>
            </tr>
        `;
                    tableBody.innerHTML += row;
                });
            }

            function updateSummary(totalDocuments, totalAmount) {
                totalDocumentsElement.textContent = `${totalDocuments} รายการ`;
                totalAmountElement.textContent = new Intl.NumberFormat('th-TH', {
                    style: 'currency',
                    currency: 'THB'
                }).format(totalAmount);
            }



            function updateChart(brandOrders) {
                const chartData = brandOrders.map(item => ({
                    name: item.categories_name,
                    y: parseFloat(item.total_quantity)
                }));

                if (chart) {
                    chart.series[0].setData(chartData);
                } else {
                    chart = Highcharts.chart('brandSalesChart', {
                        chart: {
                            type: 'pie'
                        },
                        title: {
                            text: 'รายงานแบรนด์สินค้าที่ขายดี'
                        },
                        tooltip: {
                            valueSuffix: ' ชิ้น'
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                dataLabels: {
                                    enabled: true,
                                    format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                                }
                            }
                        },
                        series: [{
                            name: 'ยอดขาย',
                            colorByPoint: true,
                            data: chartData
                        }]
                    });
                }
            }

            function updateCheckboxes() {
                const newCheckboxes = document.querySelectorAll('input[name="selected_orders[]"]');
                selectAllCheckbox.checked = newCheckboxes.length > 0 && Array.from(newCheckboxes).every(cb => cb
                    .checked);

                newCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        selectAllCheckbox.checked = Array.from(newCheckboxes).every(cb => cb
                            .checked);
                    });
                });
            }

            downloadButton.addEventListener('click', function(e) {
                e.preventDefault();
                const selectedCheckboxes = document.querySelectorAll(
                    'input[name="selected_orders[]"]:checked');
                if (selectedCheckboxes.length > 0) {
                    const selectedOrders = Array.from(selectedCheckboxes).map(cb => cb.value);
                    document.getElementById('selectedOrdersInput').value = JSON.stringify(selectedOrders);
                    downloadForm.submit();
                } else {
                    alert('กรุณาเลือกอย่างน้อยหนึ่งรายการเพื่อดาวน์โหลด');
                }
            });

            selectAllCheckbox.addEventListener('change', function() {
                const newCheckboxes = document.querySelectorAll('input[name="selected_orders[]"]');
                newCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });

            downloadButton.addEventListener('click', function(e) {
                e.preventDefault();
                const selectedCheckboxes = document.querySelectorAll(
                    'input[name="selected_orders[]"]:checked');
                if (selectedCheckboxes.length > 0) {
                    downloadForm.submit();
                } else {
                    alert('กรุณาเลือกอย่างน้อยหนึ่งรายการเพื่อดาวน์โหลด');
                }
            });

            function debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            }

            const debouncedUpdateData = debounce(updateData, 300);

            categoryFilter.addEventListener('change', debouncedUpdateData);
            startDate.addEventListener('change', debouncedUpdateData);
            endDate.addEventListener('change', debouncedUpdateData);
            searchName.addEventListener('input', debouncedUpdateData);

            // Initial data load
            updateData();
        });
    </script>
</x-app-layout>
