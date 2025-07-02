$(document).ready(function () {
    $('#changePasswordForm').on('submit', function (event) {
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

        let formData = new FormData(this);
        fetch('/change-password', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        toast: true,
                        position: 'top',
                        icon: 'success',
                        title: data.messages,
                        showConfirmButton: false,
                        timer: 3000,
                    });
                    $('#change-password').modal('hide');
                    $('#changePasswordForm')[0].reset();
                } else {
                    let errorMessage = data.messages;
                    if (typeof errorMessage === 'object') {
                        errorMessage = Object.values(errorMessage).join('<br>');
                    }
                    Swal.fire({
                        toast: true,
                        position: 'top',
                        icon: 'error',
                        title: errorMessage,
                        showConfirmButton: false,
                        timer: 3000,
                    });
                }
            })
    });
});