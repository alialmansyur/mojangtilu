<?= $this->extend('Apps/layouts/main_layout'); ?>
<?= $this->section('content'); ?>
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
        <section class="row"></section>
    </div>
</div>
</div>
<?= $this->endSection(); ?>