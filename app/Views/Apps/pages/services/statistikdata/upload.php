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
        <section class="row">
            <div class="col-md-12">
                <div class="card border border-primary">
                    <div class="card-body">
                        <form method="POST">
                            <h4 class="text-bold">Jenis Data </h4>
                            <div class="form-group">
                                <label class="form-label">Pilih jenis data yang akan di upload</label>
                                <select class="form-select select2" name="jenis">
                                    <option value="" selected disabled>Pilih salah satu</option>
                                    <option value="Jumlah ASN" <?= ($jenis == "Jumlah ASN") ? "selected" : "" ?>>Jumlah ASN</option>
                                    <option value="Golongan ASN" <?= ($jenis == "Golongan ASN") ? "selected" : "" ?>>Golongan ASN</option>
                                    <option value="Jenis Kelamin ASN" <?= ($jenis == "Jenis Kelamin ASN") ? "selected" : "" ?>>Jenis Kelamin ASN</option>
                                    <option value="Pendidikan ASN" <?= ($jenis == "Pendidikan ASN") ? "selected" : "" ?>>Pendidikan ASN</option>
                                    <option value="Usia ASN" <?= ($jenis == "Usia ASN") ? "selected" : "" ?>>Usia ASN</option>
                                </select>
                            </div>
                            <div class="text-end">
                                <button class="btn btn-primary">Terapkan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php if($_POST): ?>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="text-bold">Tambah Data</h4>
                        <form class="form form-vertical mt-3" id="statistikForm">
                            <div class="form-body">
                                <div class="row g-2">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label">Period</label>
                                            <input type="hidden" class="form-control" id="jenis" name="jenis"
                                                value="<?= $jenis; ?>">
                                            <input type="month" class="form-control" name="period">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label">Tanggal Data</label>
                                            <input type="date" class="form-control" name="syncdate">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label">File Excel</label>
                                            <input type="file" class="form-control" name="attach"
                                                placeholder="Password">
                                        </div>
                                    </div>
                                    <div class="col-12 d-flex justify-content-end mt-4">
                                        <a class="btn btn-secondary me-2">Downlaod Template</a>
                                        <button type="submit" class="btn btn-primary">Unggah</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h4 class="text-bold"><?= $jenis; ?></h4>
                        <div class="table-responsive mt-4">
                            <table class="table table-hover table-stripped table-bordered nowrap" style="width:100%" id="statistikTable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Upload By</th>
                                        <th>Period</th>
                                        <th>Sync Date</th>
                                        <th>Upload Date</th>
                                        <th></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </section>
    </div>
</div>
<?= $this->endSection(); ?>
<?= $this->section('scripts'); ?>
<script src="<?= base_url('apps/assets/js/custom/pages/services/statistikdata/uploadPage.js?v=2.2.5'); ?>"></script>
<?= $this->endSection(); ?>