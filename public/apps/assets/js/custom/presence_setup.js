$(document).ready(function () {
    $('#setupform').on('submit', function (event) {
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
            url: "/setup-configure",
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
                        timer: 2000,
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