<?= $this->extend('Apps/layouts/main_layout'); ?>
<?= $this->section('content'); ?>
<div class="page-content d-flex align-items-center justify-content-center min-vh-100">
    <div class="container-sm px-4 text-start mx-auto" style="max-width: 900px; padding: 0 2rem;">
        <h2 class="fw-bold text-primary" style="font-size: 2.4rem;">
            <strong> Halo, Ali</strong></h2>
        <h4 class="text-muted" style="font-weight: 400;">Apa yang akan kamu kerjakan hari ini ?</h4>
        <div class="row d-flex align-items-stretch mt-4 g-3">
            <div class="col-6 col-md-3 mb-4">
                <div class="card text-center h-75 shadow-sm card-hover-border">
                    <div class="card-body mb-2" data-bs-toggle="modal" data-bs-target="#quickActionModal"
                        style="cursor:pointer;">
                        <i class="bi bi-folder-check fs-2 text-primary mb-2"></i>
                        <h5 class="fw-bold">Peremajaan</h5>
                        <p class="text-muted small mb-0 mt-auto d-none d-md-block">
                            Approval peremajaan data SIASN
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-4">
                <div class="card text-center h-75 shadow-sm card-hover-border">
                    <div class="card-body mb-2" data-bs-toggle="modal" data-bs-target="#quickActionModal"
                        style="cursor:pointer;">
                        <i class="bi bi-folder-check fs-2 text-primary mb-2"></i>
                        <h5 class="fw-bold">Integrasi</h5>
                        <p class="text-muted small mb-0 mt-auto d-none d-md-block">
                            Progres integrasi SIMPEG Instansi
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-4">
                <div class="card text-center h-75 shadow-sm card-hover-border">
                    <div class="card-body mb-2" data-bs-toggle="modal" data-bs-target="#quickActionModal"
                        style="cursor:pointer;">
                        <i class="bi bi-folder-check fs-2 text-primary mb-2"></i>
                        <h5 class="fw-bold">Takah Digital</h5>
                        <p class="text-muted small mb-0 mt-auto d-none d-md-block">
                            Transformasi dokumen fisik ke bentuk digital
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-4">
                <div class="card text-center h-75 shadow-sm card-hover-border">
                    <div class="card-body mb-2" data-bs-toggle="modal" data-bs-target="#quickActionModal"
                        style="cursor:pointer;">
                        <i class="bi bi-folder-check fs-2 text-primary mb-2"></i>
                        <h5 class="fw-bold">Arsip Dinamis</h5>
                        <p class="text-muted small mb-0 mt-auto d-none d-md-block">
                            Mengelola dokumen aktif secara terstruktur
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="quickActionModal" tabindex="-1" aria-labelledby="quickActionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body p-4">
                    <h5 class="fw-bold mb-4">Quick Action</h5>
                    <div class="card mb-3 border border-1">
                        <div class="card-body d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <i class="bi bi-upload fs-2 text-primary"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1"><strong>Upload Data</strong></h6>
                                <p class="text-muted small mb-0">Unggah file atau dokumen penting ke sistem.</p>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3 border border-1">
                        <div class="card-body d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <i class="bi bi-input-cursor-text fs-2 text-primary"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1"><strong>Entry Data</strong></h6>
                                <p class="text-muted small mb-0">Masukkan data baru ke dalam sistem secara manual.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3 border border-1">
                        <div class="card-body d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <i class="bi bi-info-circle-fill fs-2 text-primary"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1"><strong>Informasi Pekerjaan</strong></h6>
                                <p class="text-muted small mb-0">Lihat detail dan status pekerjaan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>