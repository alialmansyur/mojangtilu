$(document).ready(function () {
    $('#exportTable tbody').on('click', 'tr td .btn-status', function () {
        var key = $(this).attr('data-key');
        var sts = $(this).prop('checked') ? 1 : 0;
        statusData(key, sts);
    });

    function statusData(key, sts) {

        Swal.fire({
            toast: true,
            position: 'top',
            icon: 'info',
            title: 'Sedang memproses data ...',
            showConfirmButton: false,
            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            type: "POST",
            url: "/status-data",
            data: {
                key: key,
                status: sts,
                tableinfo: 'movable',
            },
            dataType: 'json',
            success: function (response) {
                if (response.status) {
                    Swal.fire({
                        toast: true,
                        position: 'top',
                        icon: 'success',
                        title: response.message,
                        timer: 1000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                }
            }
        });
    }

    $('#exportTable tbody').on('click', 'tr td .btn-kill', function () {
        var key = $(this).attr('data-key');
        Swal.fire({
            title: "Konfirmasi",
            text: "Yakin akan menghapus data ini ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#dc3545",
            cancelButtonColor: "#435ebe",
            confirmButtonText: "Yes, Sure",
            cancelButtonText: "Cancel"
        }).then((result) => {
            if (result.isConfirmed) {
                removeData(key);
            }
        });
    });

    function removeData(key) {

        Swal.fire({
            toast: true,
            position: 'top',
            icon: 'info',
            title: 'Sedang memproses data ...',
            showConfirmButton: false,
            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            type: "POST",
            url: "/remove-data",
            data: {
                key: key,
                tableinfo: 'movable',
            },
            dataType: 'json',
            success: function (response) {
                if (response.status) {
                    Swal.fire({
                        toast: true,
                        position: 'top',
                        icon: 'success',
                        title: response.message,
                        timer: 1000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                }
            }
        });
    }

    $('#formUpload').on('submit', function (event) {
        event.preventDefault();

        Swal.fire({
            toast: true,
            position: 'top',
            icon: 'info',
            title: 'Sedang memproses data ...',
            showConfirmButton: false,
            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        var $form = $(this);
        var formData = new FormData($form[0]);
        $.ajax({
            type: "POST",
            url: "/store-data-movable",
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.status) {
                    Swal.fire({
                        toast: true,
                        position: 'top',
                        icon: 'success',
                        title: response.message,
                        timer: 1000,
                        showConfirmButton: false
                    }).then(() => {
                        $('#add-form').modal('hide');
                        location.reload();
                    });
                } else {
                    let errorMessage = response.message;
                    if (typeof errorMessage === 'object') {
                        errorMessage = Object.values(errorMessage).join('<br>');
                    }
                    Swal.fire({
                        toast: true,
                        position: 'top',
                        icon: 'error',
                        title: '<b>Ooops!</b> ' + errorMessage,
                        showConfirmButton: false,
                        timer: 1000,
                    });
                }
            }
        });
    });

});