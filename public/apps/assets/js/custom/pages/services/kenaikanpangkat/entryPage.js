var optionsKinerja = {
    series: [{
        name: "total",
        data: [310, 800, 600, 430, 540, 340, 605, 805, 430, 540, 340, 605],
    }, ],
    chart: {
        height: 100,
        type: "area",
        toolbar: {
            show: false,
        },
    },
    colors: ["#435ebe"],
    stroke: {
        width: 2,
    },
    grid: {
        show: false,
    },
    dataLabels: {
        enabled: false,
    },
    xaxis: {
        type: "datetime",
        categories: [
            "2018-09-19T00:00:00.000Z",
            "2018-09-19T01:30:00.000Z",
            "2018-09-19T02:30:00.000Z",
            "2018-09-19T03:30:00.000Z",
            "2018-09-19T04:30:00.000Z",
            "2018-09-19T05:30:00.000Z",
            "2018-09-19T06:30:00.000Z",
            "2018-09-19T07:30:00.000Z",
            "2018-09-19T08:30:00.000Z",
            "2018-09-19T09:30:00.000Z",
            "2018-09-19T10:30:00.000Z",
            "2018-09-19T11:30:00.000Z",
        ],
        axisBorder: {
            show: false,
        },
        axisTicks: {
            show: false,
        },
        labels: {
            show: false,
        },
    },
    show: false,
    yaxis: {
        labels: {
            show: false,
        },
    },
    tooltip: {
        x: {
            format: "dd/MM/yy HH:mm",
        },
    },
}

var chartkinerja = new ApexCharts(
    document.querySelector("#chart-europe"),
    optionsKinerja
)

chartkinerja.render()