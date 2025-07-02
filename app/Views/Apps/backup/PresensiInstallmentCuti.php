<?= $this->extend('Apps/layouts/main_layout'); ?>
<?= $this->section('content'); ?>

<div class="page-content">
    <div class="page-heading mb-3">
        <div class="card mb-0 pb-0">
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
                        <button class="btn btn-outline-primary me-2" data-bs-toggle="modal"
                            data-bs-target="#add-form"><i class="bi bi-plus"></i> Tambah</button>
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
                                                <a href="/pengajuan-cuti" class="btn btn-danger mt-2 me-2"><i
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
                                    <th>Mulai</th>
                                    <th>Selesai</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Approve At</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($datalist as $key => $value): ?>
                                <tr>
                                    <td><?= $key+1; ?></td>
                                    <td><strong><a href="#" class="view-installment"
                                                data-key="<?= $value['uid']; ?>"><?= $value['doc_key']; ?></a></strong>
                                    </td>
                                    <td><?= $value['leave_name']; ?></td>
                                    <td><?= $value['date_start']; ?></td>
                                    <td><?= $value['date_end']; ?></td>
                                    <td><?= $value['instance']; ?> Hari</td>
                                    <td><span
                                            class="badge bg-<?= $value['status_clr']; ?>"><?= $value['status_msg']; ?></span>
                                    </td>
                                    <td><?= $value['approved_at']; ?></td>
                                    <td>
                                        <?php if($value['status'] == 1): ?>
                                        <button class="btn btn-danger btn-sm cancel-installment"
                                            data-key="<?= $value['id']; ?>"><i class="ni ni-cross"></i>
                                            Cancel</button>
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

<div class="modal fade" role="dialog" id="add-form">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
            <div class="modal-body modal-body-xl m-4">
                <h5 class="title">Form Pengajuan</h5>
                <p>Silahkan lengkapi form pengajuan ini</p>
                <form id="formUpload" enctype="multipart/form-data">
                    <div class="row g-2">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <div class="form-group">
                                    <label class="form-label">Jenis Cuti</label>
                                    <div class="form-control-wrap">
                                        <select class="form-select" name="opt_leave" required>
                                            <option value="" selected>Pilih Salah satu</option>
                                            <?php foreach ($listcuti as $key => $value): ?>
                                            <option value="<?= $value['leave_code'] ?>"><?= $value['leave_name'] ?>
                                            </option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label">Tanggal Mulai</label>
                                <div class="form-control-wrap">
                                    <input type="date" name="txt_date_1" class="form-control date-picker date1"
                                        value="<?= date("m/d/Y") ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label">Tanggal Selesai</label>
                                <div class="form-control-wrap">
                                    <input type="date" name="txt_date_2" class="form-control date-picker date2"
                                        value="<?= date("m/d/Y") ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-label">Alasan</label>
                                <div class="form-control-wrap">
                                    <textarea class="form-control" rows="5" name="txt_reason" required></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-label" for="customFileLabel">Lampiran</label>
                                <div class="form-control-wrap">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="attach" id="customFile"
                                            accept="application/pdf">
                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row gy-4 mt-3">
                        <div class="col-12 mt-4">
                            <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                <span class="">Batal</span>
                            </button>
                            <button type="submit" class="btn btn-primary ml-1 btn-form-submit">
                                <span class="">Simpan Data</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
<?= $this->section('scripts'); ?>
<script src="<?= base_url('apps/assets/js/custom/presence_installment_cuti.js'); ?>"></script>
<?= $this->endSection(); ?>