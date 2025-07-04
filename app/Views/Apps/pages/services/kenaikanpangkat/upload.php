<?= $this->extend('Apps/layouts/main_layout'); ?>
<?= $this->section('content'); ?>
<link rel="stylesheet" href="<?= base_url('apps/assets/extensions/filepond/filepond.css'); ?>">
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
                <div class="card border border-primary">
                    <div class="card-body">
                        <p>Data di temukan sebanyak : <strong><?= count($datalist); ?></strong></p>
                        <div class="table-responsive">
                            <table id="exportTable" class="table table-hover table-stripped table-bordered nowrap"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>#</th>
                                        <th>NIP</th>
                                        <th>Nama</th>
                                        <th>Instansi</th>
                                        <th>Prosedur</th>
                                        <th>Jenis KP</th>
                                        <th>Verifikator</th>
                                        <th>Tanggal Diterima</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($datalist as $key => $value): ?>
                                        <tr>
                                            <td></td>
                                            <td><?= $key+1 ?></td>
                                            <td><?= $value['nip'] ?></td>
                                            <td><?= $value['nama'] ?></td>
                                            <td><?= $value['instansi_temp'] ?></td>
                                            <td><?= $value['jenis_prosedur'] ?></td>
                                            <td><?= $value['jenis_kp'] ?></td>
                                            <td><?= $value['verified_by'] ?></td>
                                            <td><?= $value['received_date'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
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
<script src="<?= base_url('apps/assets/js/custom/pages/services/kenaikanpangkat/uploadPage.js?v=2.2.5'); ?>"></script>
<?= $this->endSection(); ?>