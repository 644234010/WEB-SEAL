<x-app-layout>
    <div class="content-header py-3 bg-light border-bottom">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0">Shipping Dashboard</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $pendingOrderCount }}</h3>
                        <p>คำสั่งซื้อรอดำเนินการ</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-hourglass-half"></i>
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
                        <h3>{{ $shippedOrderCount }}</h3>
                        <p>จัดส่งสินค้าแล้ว</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    


    <div id="container" style="width: 100%; height: 500px;"></div>
    <script>
        const pendingOrderCount = {{ $pendingOrderCount }};
        const pendingShipmentCount = {{ $pendingShipmentCount }};
        const shippedOrderCount = {{ $shippedOrderCount }};
    
        Highcharts.chart('container', {
            chart: {
                type: 'column'
            },
            title: {
                align: 'left',
                text: 'รายงานสถานะสินค้าทั้งหมด'
            },
            accessibility: {
                announceNewData: {
                    enabled: true
                }
            },
            xAxis: {
                type: 'category',
                title: {
                    text: 'สถานะ'
                }
            },
            yAxis: {
                title: {
                    text: 'จำนวนคำสั่งซื้อ'
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
                pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}</b> คำสั่งซื้อ<br/>'
            },
            series: [{
                name: 'สถานะสินค้า',
                colorByPoint: true,
                data: [{
                    name: 'รอดำเนินการ',
                    y: pendingOrderCount
                }, {
                    name: 'กำลังดำเนินการ',
                    y: pendingShipmentCount
                }, {
                    name: 'จัดส่งแล้ว',
                    y: shippedOrderCount
                }]
            }]
        });
    </script>
    


    <div class="card">
        <div class="card-header">
            รายการสั่งซื้อล่าสุดที่รอดำเนินการ
            <a href="/Shippingreport/pendingPaymentOrders" class="float-right">ดูทั้งหมด</a>
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
