<x-app-layout>
    <div class="content-header py-3 bg-light border-bottom">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right bg-transparent p-0 m-0">
                        <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active"><a href="/report/doc" class="text-decoration-none">ออกรายงาน</a></li>
                        <li class="breadcrumb-item active"><a href="/reports/Best_selling" class="text-decoration-none">ออกรายงานแบรนสินค้าที่ขายดี</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>


    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $pendingPaymentCount }}</h3>
                        <p>ยังไม่ชำระเงิน</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-wallet"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $paidOrderCount }}</h3>
                        <p>ชำระเงินแล้ว</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-credit-card"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $pendingShipmentCount }}</h3>
                        <p>สินค้ากำลังดำเดินการจัดส่ง</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-truck"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>฿{{ number_format($totalSales, 2) }}</h3>
                        <p>ยอดขายรวม</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>฿{{ number_format($dailySales, 2) }}</h3>
                        <p>ยอดขายรายวัน</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>฿{{ number_format($monthlySales, 2) }}</h3>
                        <p>ยอดขายรายเดือน</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>{{ $shippedOrderCount }}</h3>
                        <p>จัดส่งสินค้าแล้ว</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>฿{{ number_format($yearlySales, 2) }}</h3>
                        <p>ยอดขายรายปี</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="container" style="width: 100%; height: 500px;"></div>
    <script>
        const dailySales = {{ $dailySales }};
        const monthlySales = {{ $monthlySales }};
        const yearlySales = {{ $yearlySales }};


        Highcharts.chart('container', {
            chart: {
                type: 'column'
            },
            title: {
                align: 'left',
                text: 'รายงานยอดขายทั้งหมด'
            },
            accessibility: {
                announceNewData: {
                    enabled: true
                }
            },
            xAxis: {
                type: 'category',
                title: {
                    text: 'ช่วงเวลา'
                },
                labels: {
                    formatter: function() {
                        let thaiMonths = ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน',
                            'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
                        ];
                        let date = new Date(this.value);
                        let month = thaiMonths[date.getMonth()];
                        let day = date.getDate();
                        return ``;
                    }
                }
            },
            yAxis: {
                title: {
                    text: 'ยอดขายทั้งหมด'
                }
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                series: {
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        format: '{point.y:.1f}'
                    }
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}</b> sales<br/>'
            },
            series: [{
                name: 'ยอดขาย',
                colorByPoint: true,
                data: [{
                    name: 'วัน',
                    y: dailySales,
                    drilldown: 'day-sales'
                }, {
                    name: 'เดือน',
                    y: monthlySales,
                    drilldown: 'month-sales'
                }, {
                    name: 'ปี',
                    y: yearlySales,
                    drilldown: 'year-sales'
                }]
            }],
            drilldown: {
                breadcrumbs: {
                    position: {
                        align: 'right'
                    }
                },
                series: [{
                    name: 'ยอดขายรายวัน',
                    id: 'day-sales',
                    data: [
                        @foreach ($dailySalesData as $data)
                            ['{{ $data->order_date }}', {{ $data->total }}],
                        @endforeach
                    ]
                }, {
                    name: 'ยอดขายประจำเดือน',
                    id: 'month-sales',
                    data: [
                        @foreach ($monthlySalesData as $data)
                            ['{{ $data->month }}', {{ $data->total }}],
                        @endforeach
                    ]
                }, {
                    name: 'ยอดขายประจำปี',
                    id: 'year-sales',
                    data: [
                        @foreach ($yearlySalesData as $data)
                            ['{{ $data->year }}', {{ $data->total }}],
                        @endforeach
                    ]
                }]
            }
        });
    </script>



<div class="card mt-4">
    <div class="card-header">
        รายการสั่งซื้อล่าสุดที่ยังไม่ได้ชำระเงิน
        <a href="/report/unpaid-orders" class="float-right">ดูทั้งหมด</a>
    </div>
    <div class="card-body">
        @if ($latestUnpaidOrders->isNotEmpty())
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>หมายเลขคำสั่งซื้อ</th>
                        <th>วันที่</th>
                        <th>ยอดรวม</th>
                        <th>สถานะ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($latestUnpaidOrders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->order_date }}</td>
                            <td>฿ {{ number_format($order->total_amount, 2) }}</td>
                            <td>
                                @switch($order->payment_status)
                                    @case('Pending')
                                        <span class="badge bg-warning">ปลายทาง</span>
                                    @break

                                    @case('Unpaid')
                                        <span class="badge bg-warning">ยังไม่ได้ชำระ</span>
                                    @break

                                    <span
                                        class="badge bg-secondary">{{ $order->payment_status ?? 'ไม่ทราบสถานะ' }}</span>
                                @endswitch
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>ไม่มีรายการที่ยังไม่ได้ชำระเงิน</p>
        @endif
    </div>
</div>

        <div class="card">
            <div class="card-header">
                รายการสั่งซื้อล่าสุดที่รอดำเนินการ
                <a href="/report/pendingPaymentOrders" class="float-right">ดูทั้งหมด</a>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>หมายเลขคำสั่งซื้อ</th>
                            <th>วันที่</th>
                            <th>ยอดรวม</th>
                            <th>สถานะ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($latestPendingOrders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->order_date }}</td>
                                <td>฿ {{ number_format($order->total_amount, 2) }}</td>
                                <td>
                                    @switch($order->status)
                                        @case('Pending')
                                            <span class="badge bg-warning">รอดำเนินการ</span>
                                        @break

                                        @case('Processing')
                                            <span class="badge bg-info">กำลังดำเนินการ</span>
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

</x-app-layout>
