$(function () {

    // chart teras
    var cTeras = Highcharts.chart('chart-teras', {
        credits: false,
        chart: {
            type: 'column'
        },
        title: {
            text: 'Prestasi Pencapaian Pelan Strategik'
        },
        xAxis: {
            categories: ['Teras 1', 'Teras 2', 'Teras 3', 'Teras 4', 'Teras 5']
        },
        yAxis: {
            title: {
                text: 'Peratus Pencapaian'
            },
            tickInterval: 10,
            floor: 0,
            ceiling: 100
        },
        plotOptions: {
            column: {
                colorByPoint: true
            }
        },
        series: [{
            showInLegend: false,
            data: [{
                name: 'Teras 1',
                color: '#24ff00',
                y: 80
            }, {
                name: 'Teras 2',
                color: '#8a7df1',
                y: 70
            }, {
                name: 'Teras 3',
                color: '#f44747',
                y: 78
            }, {
                name: 'Teras 4',
                color: '#53bbf5',
                y: 84
            }, {
                name: 'Teras 5',
                color: '#00ff66',
                y: 82
            }]
        },],
        tooltip: {
            formatter: function() {
                return this.x+': <b>'+this.y+'%</b>'
            }
        }
    });

    // chart kewangan
    var cKewangan = Highcharts.chart('chart-kewangan', {
        credits: false,
        chart: {
            type: 'column'
        },
        title: {
            text: 'Pencapaian Sasaran Kerja Tahunan'
        },
        xAxis: {
            categories: ['TPP', 'ICT', 'PT']
        },
        yAxis: {
            title: {
                text: 'Peratus'
            },
            tickInterval: 10,
            floor: 0,
            ceiling: 100
        },
        plotOptions: {
            column: {
                colorByPoint: true
            }
        },
        series: [{
            showInLegend: false,
            data: [{
                name: 'Teras 1',
                color: '#24ff00',
                y: 80
            }, {
                name: 'Teras 2',
                color: '#8a7df1',
                y: 70
            }, {
                name: 'Teras 3',
                color: '#f44747',
                y: 78
            }]
        },],
        tooltip: {
            formatter: function() {
                return this.x+': <b>'+this.y+'%</b>'
            }
        }
    });

    // chart skt
    var cSkt = Highcharts.chart('chart-skt', {
        credits: false,
        chart: {
            type: 'column'
        },
        title: {
            text: 'Prestasi Perbelanjaan'
        },
        xAxis: {
            categories: ['ST', 'ET', 'OT', 'BT'],
            crosshair: true
        },
        yAxis: {
            title: {
                text: 'Bilangan'
            },
            tickInterval: 10,
            floor: 0,
            ceiling: 100
        },
        plotOptions: {
            column: {
                // colorByPoint: true
            }
        },
        series: [{
            name: 'Sektor Pengurusan',
            // color: '#24ff00',
            data: [40, 30, 50, 45]
        }, {
            name: 'Sektor ICT',
            // color: '#8a7df1',
            data: [40, 50, 30, 30]
        }, {
            name: 'Sektor TPP',
            // color: '#f44747',
            data: [60, 50, 30, 50]
        }]
    });

});