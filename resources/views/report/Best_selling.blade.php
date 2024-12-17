<x-app-layout>

    <h1>รายงานแบรนด์สินค้าที่ขายดี</h1>

    <div id="container" style="width: 100%; height: 400px;"></div>

    <script>
        var brandOrders = @json($brandOrders);

        var chartData = brandOrders.map(function(item) {
            return {
                name: item.categories_name,
                y: parseFloat(item.total_quantity)
            };
        });

        Highcharts.chart('container', {
            chart: {
                type: 'pie'
            },
            title: {
                text: 'รายงานแบรนด์สินค้าที่ขายดี'
            },
            tooltip: {
                valueSuffix: ' ชิ้น'
            },
            subtitle: {
                text: 'ข้อมูลจากระบบฐานข้อมูลของคุณ'
            },
            plotOptions: {
                series: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: [{
                        enabled: true,
                        distance: 20
                    }, {
                        enabled: true,
                        distance: -40,
                        format: '{point.percentage:.1f}%',
                        style: {
                            fontSize: '1.2em',
                            textOutline: 'none',
                            opacity: 0.7
                        },
                        filter: {
                            operator: '>',
                            property: 'percentage',
                            value: 10
                        }
                    }]
                }
            },
            series: [{
                name: 'ยอดขาย',
                colorByPoint: true,
                data: chartData
            }]
        });
    </script>
    

</x-app-layout>
