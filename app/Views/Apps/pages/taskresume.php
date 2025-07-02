<?= $this->extend('Apps/layouts/main_layout'); ?>
<?= $this->section('content'); ?>
<div class="page-content d-flex align-items-center justify-content-center min-vh-100">
    <div class="page-heading">
        <div class="row align-items-center d-flex justify-content-between">
            <div class="col-md-6 text-start">
                <h3 class="mt-3"><b><?= $title; ?></b></h3>
                <p class="text-subtitle text-muted">Mojang Tilu - Kantor Regional III BKN</p>
            </div>
            <div class="col-md-6 text-end">
                <button class="btn btn-outline-primary me-2" data-bs-toggle="collapse" data-bs-target="#collapseExample"
                    aria-expanded="false"><i class="bi bi-filter"></i> Filter
                    Data</button>
            </div>
        </div>
    </div>
    <section class="row"></section>
</div>
</div>
<?= $this->endSection(); ?>