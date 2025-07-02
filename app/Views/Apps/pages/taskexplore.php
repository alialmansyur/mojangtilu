<?= $this->extend('Apps/layouts/main_layout'); ?>
<?= $this->section('content'); ?>

<style>
    .card-hover-border {
        /* transition: border 0.2s, box-shadow 0.2s !important; */
        border: 1px solid transparent !important;
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .card-hover-border:hover {
        border: 1px solid var(--bs-primary) !important;
        transform: translateY(-4px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        /* box-shadow: 0 0 0 0.1rem rgba(67, 94, 190, 0.25) !important; */
    }

    .icon-circle {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background-color: rgba(13, 110, 253, 0.1);
        position: relative;
        overflow: hidden;
    }

    .icon-bottom {
        position: absolute;
        bottom: 60%;
        left: 45%;
        transform: translateX(-70%);
    }
</style>

<div class="page-content">
    <div class="container-sm px-4 text-start mx-auto">
        <div class="page-heading">
            <div id="layanan-container"></div>
            <div class="row align-items-center d-flex justify-content-between">
                <div class="col-md-6 text-start">
                    <h3 class="mt-3"><b><?= $title; ?></b></h3>
                    <p class="text-subtitle text-muted">Mojang Tilu - Kantor Regional III BKN</p>
                </div>
                <div class="col-md-6 text-end">
                    <button class="btn btn-outline-primary me-2" data-bs-toggle="collapse"
                        data-bs-target="#collapseExample" aria-expanded="false"><i class="bi bi-filter"></i> Filter
                        Data</button>
                </div>
            </div>
        </div>
        <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab"
                    aria-controls="home" aria-selected="true"><strong>Semua Data</strong></a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab"
                    aria-controls="profile" aria-selected="false"><strong>Untuk Saya</strong></a>
            </li>
        </ul>
        <h5 class="text-bold mb-4" style="font-weight: 400;">Pilih layanan untuk di enroll ke dalam task kamu</h5>
        <div class="position-relative">
            <input type="search" id="searchdata" class="form-control rounded form-control-lg pe-5"
                placeholder="Cari tugas disini" style="border-radius:0.95em !important">
            <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y me-2 p-0"
                disabled>
                <i class="bi bi-search fs-4 text-primary"></i>
            </button>
        </div>
        <input type="hidden" id="unit" value=0>
        <div class="row mt-4">
            <div class="col-3">
                <div class="nav flex-column nav-pills" role="tablist" aria-orientation="vertical">
                    <a class="nav-link bidang rounded active" data-key="0" data-bs-toggle="pill" href="#started"
                        role="tab">Semua Bidang</a>
                    <a class="nav-link bidang rounded" data-key="2" data-bs-toggle="pill" href="#" role="tab">Bidang
                        TU</a>
                    <a class="nav-link bidang rounded" data-key="4" data-bs-toggle="pill" href="#" role="tab">Bidang
                        INKA</a>
                    <a class="nav-link bidang rounded" data-key="6" data-bs-toggle="pill" href="#" role="tab">Bidang
                        PENSIUN</a>
                    <a class="nav-link bidang rounded" data-key="3" data-bs-toggle="pill" href="#" role="tab">Bidang
                        MUTASI</a>
                    <a class="nav-link bidang rounded" data-key="5" data-bs-toggle="pill" href="#" role="tab">Bidang
                        PDSK</a>
                </div>
            </div>
            <div class="col-9">
                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="started">
                        <div class="row d-flex align-items-stretch g-3" id="loaded">
                            <div class="col-md-12" id="spinLoadData">
                                <div class="d-flex justify-content-center align-items-center" style="height:500px;">
                                    <div class="text-center text-primary">
                                        <span class="spinner-border spinner-border-sm me-2 text-primary" role="status"
                                            aria-hidden="true"></span>
                                        Processing...
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<?= $this->endSection(); ?>
<?= $this->section('scripts'); ?>
<script>
    async function loadData(keyword = '') {
        var unit = $('#unit').val();
        const response = await fetch('/fetch-layanan', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                keyword: keyword,
                unit: unit,
            })
        });
        const data = await response.json();
        pageLoaded(data);
    }

    $('.bidang').on('click', function () {
        unit = $(this).attr('data-key');
        $('#unit').val(unit);
        loadData();
    });

    $('#searchdata').on('input', function () {
        const keyword = $(this).val();
        loadData(keyword);
    });

    $('#searchdata').on('keypress', function (e) {
        if (e.which === 13) {
            const keyword = $(this).val();
            loadData(keyword);
        }
    });

    function pageLoaded(data) {
        $('#loaded').html('');
        $('#spinLoadData').addClass('d-none');
        if (Array.isArray(data.list) && data.list.length === 0) {
            var card = `
            <div class="col-md-12">
                <div class="d-flex justify-content-center align-items-center" style="height:500px;">
                    <div class="text-center">
                        <div style="width: 100%;">
                            <iframe
                                src="https://lottie.host/embed/32e72f58-9db0-476b-895e-185c0566d08f/Klshx3kbL6.lottie"
                                style="width: 100%; height: 300px; border: none;" allowfullscreen>
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
            `;
            $('#loaded').append(card);
        } else {
            $.each(data.list, function (index, value) {
                var card = `
                    <div class="col-12 col-md-3">
                        <div class="card text-center shadow-sm h-100 card-hover-border card-task" data-key="${value.id}" data-alias="${value.alias}">
                            <div class="card-body" style="cursor:pointer;">
                                <i class="bi bi-folder-plus fs-1 text-primary mb-2"></i>
                                <h5 class="fw-bold">${value.alias}</h5>
                                <p class="text-muted small mb-0 mt-auto d-none d-md-block">
                                    Layanan Kanreg III BKN Bandung
                                </p>
                            </div>
                        </div>
                    </div>
                `;
                $('#loaded').append(card);
            });
        }

        $('.card-task').on('click', function () {
            var keydata = $(this).attr('data-key');
            var aliases = $(this).attr('data-alias');
            var key = $(this).attr('data-key');
            Swal.fire({
                title: "Konfirmasi",
                text: "Apakah kamu yakin akan enroll " +aliases+ " ?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#dc3545",
                cancelButtonColor: "#435ebe",
                confirmButtonText: "Ya, Saya akan ambil",
                cancelButtonText: "Batalkan"
            }).then((result) => {
                if (result.isConfirmed) {
                    // enrollTask(keydata);
                }
            });
        });
    }

    function swlErrorHandler(msg) {
        Swal.fire({
            toast: true,
            position: 'top',
            icon: 'error',
            title: msg,
            timer: 2000,
            showConfirmButton: false
        });
    }


    loadData();
</script>

<?= $this->endSection(); ?>