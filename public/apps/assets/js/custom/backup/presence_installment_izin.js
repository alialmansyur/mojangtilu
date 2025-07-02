$(document).ready(function () {

    $('select[name="opt_leave"]').change(function () {
        var selectedEventId = $(this).val();
        $('#opt1, #opt2').addClass('d-none').removeAttr('required');
        if (selectedEventId === 'LV07' || selectedEventId === 'LV08') {
            $('#opt1, #opt2').removeClass('d-none').attr('required', true);
        }
    });

    var keyGlobe;

    $('.dtable tbody').on('click', 'tr td .view-installment', function () {
        var key = $(this).attr('data-key');
        viewDataInstallment(key);
        keyGlobe = key;
    });

    function viewDataInstallment(key) {
        $.ajax({
            type: "POST",
            url: "/detail-installment",
            data: {
                paramkey: key
            },
            dataType: 'json',
            success: function (response) {
                if (response) {
                    $('#view-modal').modal('show');
                    $('.code').val(response.data[0].doc_key);
                    $('.userid').val(response.data[0].user_id);
                    $('.name').val(response.data[0].nama);
                    $('.group').val(response.data[0].leave_name);
                    $('.category').val(response.data[0].leave_init);
                    $('.start').val(response.data[0].date_start);
                    $('.end').val(response.data[0].date_end);
                    $('.time').val(response.data[0].time_attend);
                    $('.instance').val(response.data[0].instance);
                    $('.reason').val(response.data[0].leave_note);

                    var fileName = response.data[0].file_name;
                    var filePath = '/upload/leave/' + fileName;
                    $('.attach').html(`<a href="${filePath}" download>${fileName}</a>`);
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
            },
            error: function (xhr, status, error) {
                let errorMessage = xhr.responseJSON && xhr.responseJSON.message ?
                    xhr.responseJSON.message :
                    "Terjadi kesalahan saat mengambil data. Status: " + status;
                Swal.fire({
                    toast: true,
                    position: 'top',
                    icon: 'error',
                    title: '<b>Request Failed!</b> ' + errorMessage,
                    showConfirmButton: false,
                    timer: 1000,
                });

                console.error("AJAX Error:", {
                    status: xhr.status,
                    responseText: xhr.responseText,
                    errorThrown: error
                });
            }
        });
    }

    $('.dtable tbody').on('click', 'tr td .cancel-installment', function () {
        var key = $(this).attr('data-key');
        var status = 5;
        Swal.fire({
            text: "Apa anda yakin akan membatalkan pengajuan ini?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d63031",
            // cancelButtonColor: "#f5f6fa",
            confirmButtonText: "Ya",
            cancelButtonText: "Tidak"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: "/cancel-installment",
                    data: {
                        key: key,
                        status: status
                    },
                    success: function (response) {
                        if (response) {
                            location.reload();
                        }
                    }
                });
            }
        });
    });

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
            url: "/submit-installment",
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
                        timer: 2000,
                    });
                }
            }
        });
    });
});