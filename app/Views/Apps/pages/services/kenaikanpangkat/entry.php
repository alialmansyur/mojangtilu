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
        <section class="row g-3">
            <div class="col-md-4">
                <h5 class="mb-0 ms-3">Grafik Kinerja</h5>
                <div class="col-12">
                    <div id="chart-europe"></div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card">
                    <div class="card-body">
                        <h6 class="text-muted font-semibold">Total</h6>
                        <h2 class="font-extrabold mb-0"><?= count($datalist); ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card">
                    <div class="card-body">
                        <h6 class="text-muted font-semibold">BTS</h6>
                        <h2 class="font-extrabold mb-0">0</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card">
                    <div class="card-body">
                        <h6 class="text-muted font-semibold">TMS</h6>
                        <h2 class="font-extrabold mb-0">0</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card">
                    <div class="card-body">
                        <h6 class="text-muted font-semibold">ACC</h6>
                        <h2 class="font-extrabold mb-0">0</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab"
                                    aria-controls="home" aria-selected="true"><strong>Data Baru</strong></a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab"
                                    aria-controls="profile" aria-selected="false"><strong>BTS</strong></a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab"
                                    aria-controls="profile" aria-selected="false"><strong>TMS</strong></a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab"
                                    aria-controls="profile" aria-selected="false"><strong>ACC</strong></a>
                            </li>
                        </ul>
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
<script src="<?= base_url('apps/assets/extensions/apexcharts/apexcharts.min.js'); ?>"></script>
<script src="<?= base_url('apps/assets/js/custom/pages/services/kenaikanpangkat/entryPage.js?v=2.2.5'); ?>"></script>
<?= $this->endSection(); ?>