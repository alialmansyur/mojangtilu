let cropper;
const profileImage = document.getElementById('profileImage');
const image = document.getElementById('preview');

image.addEventListener('error', () => {
    image.src = profileImage.src;
});

document.getElementById('inputImage').addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (event) {
            image.src = event.target.result;
            if (cropper) {
                cropper.destroy();
            }
            cropper = new Cropper(image, {
                aspectRatio: 1,
                viewMode: 1
            });
        }
        reader.readAsDataURL(file);
    }
});

document.getElementById('cropAndUpload').addEventListener('click', function () {
    if (!cropper) {
        Swal.fire({
            toast: true,
            position: 'top',
            icon: 'error',
            title: 'Silakan pilih gambar dulu.',
            timer: 2000,
            showConfirmButton: false
        });
        return;
    }

    cropper.getCroppedCanvas({
        width: 500,
        height: 500
    }).toBlob(function (blob) {
        const formData = new FormData();
        formData.append('avatar', blob, 'avatar.jpg');

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

        fetch("/uploadAvatar", {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                Swal.close();
                if (data.status === 'success') {
                    Swal.fire({
                        toast: true,
                        position: 'top',
                        icon: 'success',
                        title: data.message,
                        timer: 1000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        toast: true,
                        position: 'top',
                        icon: 'warning',
                        title: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    return;
                }
            })
    }, 'image/jpeg', 0.9);
});

$(document).ready(function () {
    $('#profilData').on('submit', function (event) {

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

        event.preventDefault();
        var $form = $(this);
        var formData = new FormData($form[0]);
        $.ajax({
            type: "POST",
            url: "/update-profil-data",
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