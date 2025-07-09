$(document).ready(function () {

    $('.btn-pull-data').on('click', function () {
        swlwaitProsessing()
        $.ajax({
            url:  AppConfig.initGlobal + 'allocation/pull-task',
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                Swal.close();
                if (response.status) {
                    swlSuccess()
                } else {
                    swlErrorHandler(response.message || 'Failed to pull data!')
                }
            }
        });
    });

    $('#usulanTable tbody').on('click', 'tr td .btn-verify', function () {
        // var key = $(this).attr('data-key');
        var key = $(this).attr('data-key');
        var nip = $(this).attr('data-nip');
        $('#nip').val(nip)
        $('#pertekModal').modal('show');
    });

    const $statusSelect = $('#status');
    const $reasonTextarea = $('#reason');
    $statusSelect.on('change', function () {
        const selected = $(this).val();
        if (selected === 'BTS' || selected === 'TMS') {
            $reasonTextarea.prop('disabled', false);
        } else {
            $reasonTextarea.val('');
            $reasonTextarea.prop('disabled', true);
        }
    });

    $('#pertekForm').on('submit', function (e) {
        e.preventDefault();
        const data = {
            noPertek: $('#noPertek').val(),
            status: $('#status').val(),
            reason: $('#reason').val()
        };
        console.log('Form data:', data);
    });

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
    $('#usulanTable').DataTable({
        responsive: {
            details: {
                type: 'column',
                target: 0
            }
        },
        columnDefs: [{
            className: 'control',
            orderable: false,
            targets: 0
        }],
        dom: 'Bfrtip',
        buttons: [
            'copy', 'excel', 'pdf', 'print'
        ],
        order: [1, 'asc']
    });

    function swlErrorHandler(msg) {
        Swal.fire({
            toast: true,
            position: 'top',
            icon: 'error',
            title: msg,
            timer: 3000,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
        });
    }

    function swlSuccess() {
        Swal.fire({
            toast: true,
            position: 'top',
            icon: 'success',
            title: 'Data berhasil di simpan',
            timer: 3000,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
        }).then(() => {
            window.location.reload();
        });
    }

    function swlwaitProsessing() {
        Swal.fire({
            toast: true,
            position: 'top',
            icon: 'info',
            title: 'Permintaan sedang di proses ...',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }

});