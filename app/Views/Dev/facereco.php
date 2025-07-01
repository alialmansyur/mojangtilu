<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Presensi Kamera Otomatis</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

    <h2>Ambil Foto Presensi</h2>

    <form id="cameraForm" enctype="multipart/form-data">
        <input type="file" name="image" id="cameraInput" accept="image/*" capture="environment" style="display:none;"
            required>
        <button type="button" id="openCameraBtn">Buka Kamera</button>
    </form>

    <script>
        $(document).ready(function () {

            $('#openCameraBtn').click(function () {
                $('#cameraInput').click();
            });

            $('#cameraInput').on('change', function () {
                if (this.files && this.files[0]) {
                    uploadPhoto(this.files[0]);
                }
            });

            function uploadPhoto(file) {
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Mohon tunggu',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                var formData = new FormData();
                formData.append('image', file);

                $.ajax({
                    url: "<?= base_url('recognize') ?>",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        Swal.close();

                        if (response.error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.error
                            });
                        } else {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                html: '<pre>' + JSON.stringify(response, null, 2) + '</pre>'
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        Swal.close();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Gagal terhubung ke server: ' + error
                        });
                    }
                });
            }
        });
    </script>
</body>

</html>