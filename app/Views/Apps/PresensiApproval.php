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
                <div class="col-md-12">
                    <div class="collapse" id="collapseExample">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-4">Filter Data</h4>
                                <form method="POST">
                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="first-name-column">Periode Awal</label>
                                                <input type="date" class="form-control mt-2" name="start" required
                                                    value="<?= $start ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="last-name-column mb-2">Periode Akhir</label>
                                                <input type="date" class="form-control mt-2" name="end" required
                                                    value="<?= $end ?>">
                                            </div>
                                        </div>
                                        <div class="col-12 d-flex justify-content-end">
                                            <a href="/riwayat-agenda" class="btn btn-danger mt-2 me-2"><i
                                                    class="bi bi-x"></i> Reset</a>
                                            <button type="submit" class="btn btn-primary mt-2"><i
                                                    class="bi bi-search"></i> Terapkan Filter</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="row">
        <?php if($datalist): ?>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <p>Data di temukan sebanyak : <strong><?= count($datalist); ?> Data</strong></p>
                    <div class="table-responsive">
                        <table id="exportTable" class="table table-hover table-bordered nowrap dtable"
                            style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Keydoc</th>
                                    <th>Jenis Izin</th>
                                    <th>Pegawai</th>
                                    <th>Mulai</th>
                                    <th>Selesai</th>
                                    <th>Dibuat</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($datalist as $key => $value): ?>
                                <tr>
                                    <td><?= $key+1; ?></td>
                                    <td><?= $value['doc_key']; ?></td>
                                    <td><?= $value['leave_name']; ?></td>
                                    <td><?= $value['nama']; ?> <br><small
                                            class="text-muted"><?= $value['nip'] ?></small></td>
                                    <td><?= ($value['date_start'] != null) ? $value['date_start'] : "-"; ?></td>
                                    <td><?= ($value['date_end'] != null) ? $value['date_end'] : "-"; ?></td>
                                    <td><?= $value['created_at']; ?></td>
                                    <td>
                                        <?php if($value['status'] == 1): ?>
                                        <button class="btn btn-primary btn-sm view-installment"
                                            data-key="<?= $value['uid']; ?>"><i class="ni ni-cross"></i>
                                            Approve</button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="col-md-12">
            <div class="card card-bordered border-primary d-flex justify-content-center align-items-center vh-100">
                <div class="card-inner card-inner-lg ">
                    <div class="text-center">
                        <div style="width: 100%;">
                            <iframe
                                src="https://lottie.host/embed/32e72f58-9db0-476b-895e-185c0566d08f/Klshx3kbL6.lottie"
                                style="width: 100%; height: 300px; border: none;" allowfullscreen>
                            </iframe>
                        </div>
                        <div class="mx-auto mt-4">
                            <h4><b>No data</b></h4>
                            <p>Belum ada data untuk saat ini</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </section>
</div>

<?= $this->endSection(); ?>
<?= $this->section('scripts'); ?>
<script src="<?= base_url('apps/assets/js/custom/presence_approval.js?v=2'); ?>"></script>
<?= $this->endSection(); ?>