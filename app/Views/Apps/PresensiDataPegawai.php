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
                    <button class="btn btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#add-form"><i
                            class="bi bi-plus"></i> Tambah Data</button>
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
                        <table id="exportTable" class="table table-hover table-stripped table-bordered nowrap"
                            style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>IDP</th>
                                    <th>Nama</th>
                                    <th>Posisi</th>
                                    <th>Status</th>
                                    <th>Update At</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($datalist as $key => $value): ?>
                                <tr>
                                    <td><?= $key+1; ?></td>
                                    <td><?= $value['nip'] ?></td>
                                    <td><?= $value['nama'] ?></td>
                                    <td><?= $value['posisiname'] ?></td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input btn-status" type="checkbox"
                                                id="flexSwitchCheckChecked"
                                                <?= $value['is_status'] == 1 ? "checked" : ""; ?> name="status_poli"
                                                data-key="<?= $value['id']; ?>">
                                            <label class="form-check-label"
                                                for="flexSwitchCheckChecked"><?= $value['is_status'] == 1 ? "Aktif" : "Non-Aktif"; ?></label>
                                        </div>
                                    </td>
                                    <td><?= $value['updated_at'] ?></td>
                                    <td>
                                        <button class="btn icon btn-danger btn-sm btn-kill"
                                            data-key="<?= $value['id']; ?>"><i class="bi bi-trash"></i></button>
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
                <h5 class="title">Tambah Data</h5>
                <p>Lengkapi form ini untuk menambahkan data</p>
                <form id="formtrx">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Batal</span>
                    </button>
                    <button type="button" class="btn btn-primary ml-1 btn-form-submit">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Simpan Data</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
<?= $this->section('scripts'); ?>
<script src="<?= base_url('apps/'); ?>assets/js/custom/presence_data_pegawai.js"></script>
<?= $this->endSection(); ?>