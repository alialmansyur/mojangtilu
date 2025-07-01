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
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="row">
        <div class="col-md-6">

            <?php if($infosetup[0]['setting_value'] == 1): ?>
            <div class="alert alert-success alert-dismissible show fade rounded mb-4">
                Presensi Actived
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php else: ?>
            <div class="alert alert-danger alert-dismissible show fade rounded mb-4">
                Presensi Deactived
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    Pengaturan
                </div>
                <div class="card-body">
                    <form id="setupform" autocomplete="off">
                        <div class="row p-3">
                            <div class="col-md-4">
                                <label>Izinkan Presensi</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <div class="form-check form-switch">
                                    <input class="form-check-input btn-status" type="checkbox"
                                        id="flexSwitchCheckChecked" name="status_presence" data-key=""
                                        <?= $infosetup[0]['setting_value'] == 1 ? "checked" : ""; ?>>
                                    <label class="form-check-label"
                                        for="flexSwitchCheckChecked"><?= $infosetup[0]['setting_value'] == 1 ? "Actived" : "Deactived"; ?></label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label>Period IN</label>
                                <p><small class="text-muted mt-0 pt-0">Dalam format jam</small></p>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="time" class="form-control" name="set_presence_in" required
                                    value="<?= $infosetup[1]['setting_value'] ?>">
                            </div>
                            <div class="col-md-4">
                                <label>Period OUT</label>
                                <p><small class="text-muted mt-0 pt-0">Dalam format jam</small></p>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="time" class="form-control" name="set_presence_out" required
                                    value="<?= $infosetup[2]['setting_value'] ?>">
                            </div>
                            <div class="col-md-4">
                            </div>
                            <div class="col-md-8 form-group">
                                <button type="submit" class="btn btn-primary">Update Setting</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<?= $this->endSection(); ?>
<?= $this->section('scripts'); ?>
<script src="<?= base_url('apps/'); ?>assets/js/custom/presence_setup.js"></script>
<?= $this->endSection(); ?>