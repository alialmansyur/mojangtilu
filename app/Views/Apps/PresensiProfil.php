<?= $this->extend('Apps/layouts/main_layout'); ?>
<?= $this->section('content'); ?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<style>
    #preview {
        width: 300px;
        height: 300px;
        border: 1px solid #ccc;
    }
</style>

<div class="page-content">

    <div class="page-heading mb-3">
        <div class="card mb-0 pb-0">
            <div class="card-body p-2 px-4">
                <div class="row align-items-center d-flex justify-content-between">
                    <div class="col-md-6 text-start">
                        <h3 class="mt-3"><b><?= $title; ?></b></h3>
                        <p class="text-subtitle text-muted">Presensi berbasis lokasi KR III BKN</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="row row match-height">
        <div class="col-12 col-lg-4 mb-0">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-center align-items-center flex-column">
                        <img id="profileImage"
                            src="<?= base_url(($avatar != null) ? $avatar : '/apps/assets/images/faces/1.jpg'); ?>"
                            class="img w-50 rounded" style="border-radius:50% !important">
                        <h3 class="mt-3"><?= $seslog['fullname'] ?></h3>
                        <p class="text-small"><?= $seslog['username'] ?></p>
                    </div>
                </div>
                <div class="list-group">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#cropModal" style="cursor:pointer;"
                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center p-3">
                        <div class="d-flex align-items-center">
                            <span>Ubah Foto Profil</span>
                        </div>
                        <i class="bi bi-chevron-right mb-2"></i>
                    </a>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#change-password" style="cursor:pointer;"
                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center p-3">
                        <div class="d-flex align-items-center">
                            <span>Ubah Kata Sandi</span>
                        </div>
                        <i class="bi bi-chevron-right mb-2"></i>
                    </a>
                    <a href="#" onclick="logout()"
                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center text-danger p-3">
                        <div class="d-flex align-items-center">
                            <span><b>Sign Out</b></span>
                        </div>
                        <i class="bi bi-chevron-right mb-2"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-8 mt-0 mb-4">
            <div class="card">
                <div class="card-body">
                    <form id="profilData">
                        <div class="form-group">
                            <label for="name" class="form-label">Nama Panggilan</label>
                            <input type="text" name="nickname" id="name" class="form-control"
                                placeholder="Nama Panggilan" value="<?= $profil->nickname; ?>">
                        </div>
                        <div class="form-group">
                            <label for="email" class="form-label">Email</label>
                            <input type="text" name="tempemail" id="email" class="form-control" placeholder="Email" value="<?= $profil->email; ?>">
                        </div>
                        <div class="form-group">
                            <label for="phone" class="form-label">No.HP/Whatsapp</label>
                            <input type="text" name="tempphone" id="phone" class="form-control"
                                placeholder="No.HP/Whatsapp" value="<?= $profil->phone; ?>">
                        </div>
                        <div class="form-group">
                            <label for="birthday" class="form-label">Alamat</label>
                            <textarea class="form-control" name="tempaddress" rows=3><?= $profil->address; ?></textarea>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary mt-2">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="cropModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ubah Foto Profil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="file" id="inputImage" accept="image/*" capture="environment" class="form-control mb-3">
                <div class="text-center">
                    <img id="preview" src="#" alt="Preview" class="img-fluid">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" id="cropAndUpload" class="btn btn-primary">Crop & Simpan</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
<?= $this->section('scripts'); ?>
<script src="<?= base_url('apps/assets/js/custom/presence_profil_data.js?v=2'); ?>"></script>
<?= $this->endSection(); ?>