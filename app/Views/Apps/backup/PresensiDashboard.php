<?= $this->extend('Apps/layouts/main_layout'); ?>
<?= $this->section('content'); ?>

<div class="page-heading">
    <div class="card shadow-sm">
        <div class="card-body p-2 px-4">
            <div class="row align-items-center d-flex justify-content-between">
                <div class="col-md-6 text-start">
                    <h3 class="mt-3"><b><?= $title; ?></b></h3>
                    <p class="text-subtitle text-muted">PRESENSI - Sistem Presensi PPNPN</p>
                </div>
                <div class="col-md-6 text-end">
                    <button class="btn btn-outline-primary me-2" data-bs-toggle="collapse"
                        data-bs-target="#collapseExample" aria-expanded="false"><i class="bi bi-filter"></i> Filter
                        Data</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="row g-gs justify-content-center">
        <div class="col-12 col-lg-6 col-md-6">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-xl">
                            <img src="<?= base_url(($avatar != null) ? $avatar : '/apps/assets/images/faces/1.jpg'); ?>">
                        </div>
                        <div class="ms-3 name">
                            <h5 class="font-bold"><?= $seslog['fullname'] ?></h5>
                            <h6 class="text-muted mb-0"><?= $seslog['username'] ?></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-2 col-md-6">
            <div class="card">
                <div class="card-body px-4 py-4-5 mb-2 mt-1">
                    <h6 class="text-muted font-semibold">Stok Cuti</h6>
                    <h6 class="font-extrabold mb-0 c">0 Hari</h6>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-2 col-md-6">
            <div class="card">
                <div class="card-body px-4 py-4-5 mb-2 mt-1">
                    <h6 class="text-muted font-semibold">Total Presensi</h6>
                    <h6 class="font-extrabold mb-0 d">0 Hari</h6>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-2 col-md-6">
            <div class="card">
                <div class="card-body px-4 py-4-5 mb-2 mt-1">
                    <h6 class="text-muted font-semibold">Waktu Kerja</h6>
                    <h6 class="font-extrabold mb-0 w">0 Jam</h6>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body p-4">
                    <div id="calendar" style="min-height: 80vh;"></div>
                </div>
            </div>
        </div>
    </section>
</div>

<?= $this->endSection(); ?>
<?= $this->section('scripts'); ?>
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script src="<?= base_url('apps/'); ?>assets/js/custom/presence_dashboard.js?v=2"></script>
<?= $this->endSection(); ?>