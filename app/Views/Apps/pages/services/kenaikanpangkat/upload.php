<?= $this->extend('Apps/layouts/main_layout'); ?>
<?= $this->section('content'); ?>
<link rel="stylesheet" href="<?= base_url('apps/assets/extensions/filepond/filepond.css'); ?>">

<style>
    .upload-card {
        font-weight: 600;
        font-size: 1.1rem;
        text-align: center;
        padding: 1em;
    }

    .upload-card .card-body {
        padding: 1rem;
        border: 2px dashed #aaa;
        border-radius: 8px;
        background-color: #ffffff;
    }

    .filepond--credits {
        display: none !important;
    }

    .filepond--root {
        margin-top: 1em;
        background-color: transparent !important;
        border: none;
        box-shadow: none;
        border-radius: 0.95em !important;
    }

    .filepond--panel-root {
        background-color: transparent !important;
        border: none;
        border-radius: 0.95em !important;
    }

    .filepond--drop-label {
        background: transparent !important;
    }

    .filepond--file,
    .filepond--file-wrapper,
    .filepond--panel {
        border-radius: 0.95em !important;
    }
</style>

<div class="page-content">
    <div class="container-sm px-4 text-start mx-auto">
        <div class="page-heading">
            <div class="row align-items-center d-flex justify-content-between">
                <div class="col-md-6 text-start">
                    <h3 class="mt-3"><b><?= $title; ?></b></h3>
                    <p class="text-subtitle text-muted">Mojang Tilu - Kantor Regional III BKN</p>
                </div>
                <div class="col-md-6 text-end">
                    <a href="/taskme" class="btn btn-outline-primary me-2"><i class="bi bi-chevron-left"></i></a>
                    <button class="btn btn-primary me-2" data-bs-toggle="collapse" data-bs-target="#collapseExample"
                        aria-expanded="false"><i class="bi bi-filter"></i> Filter
                        Data</button>
                </div>
            </div>
        </div>
        <div class="alert alert-warning alert-dismissible show fade rounded mb-4">
            <strong>Perhatian</strong> : Data yang di tampilkan merupakan data tanggal berjalan (<i>current date</i>)
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <section class="row">
            <div class="col-md-12">
                <div class="card upload-card">
                    <div class="card-body">
                        <h5 class="mt-4">Upload Data Tarikan SIASN</h5>
                        <div class="">
                            <input type="file" class="basic-filepond" name="file" id="excelUpload"
                                accept=".xls,.xlsx" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <p>Data di temukan sebanyak : <strong></strong></p>
                        <div class="table-responsive">
                            <table id="exportTable" class="table table-hover table-stripped table-bordered nowrap"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>IDP</th>
                                        <th>Nama</th>
                                        <th>Posisi</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                        <th>Di buat</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
</div>
<?= $this->endSection(); ?>
<?= $this->section('scripts'); ?>
<script src="<?= base_url('apps/assets/extensions/filepond/filepond.js'); ?>"></script>
<script>
    const inputElement = document.querySelector('.basic-filepond');
    const pond = FilePond.create(inputElement);

    FilePond.setOptions({
        server: {
            process: (fieldName, file, metadata, load, error, progress, abort) => {
                swlwaitProsessing();
                const formData = new FormData();
                formData.append(fieldName, file, file.name);
                const request = new XMLHttpRequest();
                request.open('POST', '/store/master-data');
                request.upload.onprogress = (e) => {
                    progress(e.lengthComputable, e.loaded, e.total);
                };
                request.onload = function () {
                    if (request.status >= 200 && request.status < 300) {
                        const response = JSON.parse(request.responseText);
                        load(request.responseText);
                        swlSuccess();
                    } else {
                        swlErrorHandler("Opps Terjadi kesalahan ! " + request.responseText)
                    }
                };
                request.send(formData);
                return {
                    abort: () => {
                        request.abort();
                        abort();
                    }
                };
            }
        },
        labelIdle: 'Drag & Drop file Excel atau <span class="filepond--label-action">Browse</span>',
        labelFileTypeNotAllowed: 'File hanya boleh Excel (.xls, .xlsx)',
        acceptedFileTypes: [
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ],
        fileValidateTypeLabelExpectedTypes: 'Hanya file Excel (.xls, .xlsx) yang diperbolehkan',
        credits: false,
        fileValidateTypeDetectType: (source, type) => new Promise((resolve) => resolve(type))
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
            title: 'Data berhasil di upload, cek pada tabel pada halaman ini',
            timer: 3000,
            showConfirmButton: false
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
            showConfirmButton: false,
            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }
</script>
<?= $this->endSection(); ?>