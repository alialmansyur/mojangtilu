$(document).ready(function () {
    var jenis = $('#jenis').val();

    $('#myTable').on('processing.dt', function (e, settings, processing) {
        var tbody = $(this).find('tbody');
        if (processing) {
            tbody.html(`
            <tr>
                <td colspan="5" class="text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                </td>
            </tr>
            `);
        }
    });

    $('#statistikTable').DataTable({
        responsive: false,
        processing: false,
        serverSide: true,
        order: [[1, 'asc']],
        ajax: {
            url:  AppConfig.initGlobal + 'store/pull-datalist',
            type: 'POST',
            data: d => { d.jenis = jenis; }
        },
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'created_by' },
            { data: 'period' },
            { data: 'date' },
            { data: 'created_at' },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return `<button class="btn btn-sm btn-danger btn-remove" data-id="${row.id}"><i class='bi bi-trash'></i></button>`;
                }
            }
        ]
    });

    $('#statistikTable tbody').on('click', 'tr td .btn-remove', function () {
        var key = $(this).attr('data-id');
        Swal.fire({
            text: "Apa anda yakin akan mengahapus data ini ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d63031",
            confirmButtonText: "Ya",
            cancelButtonText: "Tidak"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url:  AppConfig.initGlobal + "store/remove-data",
                    data: {key: key, jenis:jenis},
                    success: function (response) {
                        if (response) {
                            $('#statistikTable').DataTable().ajax.reload();
                        }
                    }
                });
            }
        });
    });

    $('#statistikForm').on('submit', function (event) {
        event.preventDefault();
        swlwaitProsessing();
        var $form = $(this);
        var formData = new FormData($form[0]);
        $.ajax({
            type: "POST",
            url:  AppConfig.initGlobal + "store/import-excel",
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.status) {
                    swlSuccess()
                } else {
                    let errorMessage = response.message;
                    if (typeof errorMessage === 'object') {
                        errorMessage = Object.values(errorMessage).join('<br>');
                    }
                    swlErrorHandler(errorMessage);
                }
            }
        });
    });
});

function swlErrorHandler(msg) {
    Swal.fire({
        toast: true,
        position: 'top',
        icon: 'error',
        title: msg,
        timer: 3000,
        showConfirmButton: false
    });
}

function swlSuccess() {
    Swal.fire({
        toast: true,
        position: 'top',
        icon: 'success',
        title: 'Data berhasil di simpan',
        timer: 3000,
        showConfirmButton: false
    }).then(() => {
        $('#statistikTable').DataTable().ajax.reload();
        $('#statistikForm')[0].reset();
    });
}

function swlwaitProsessing() {
    Swal.fire({
        toast: true,
        position: 'top',
        icon: 'info',
        title: 'Permintaan sedang di proses ...',
        showConfirmButton: false,
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading();
        }
    });
}