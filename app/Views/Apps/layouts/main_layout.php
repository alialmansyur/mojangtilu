<?= view('Apps/partials/header'); ?>
<?= $this->renderSection('style'); ?>
<?= view('Apps/partials/sidebar'); ?>
<div id="main" class="mt-0 p-4">
    <header>
        <nav class="navbar navbar-expand navbar-light navbar-top p-0 mt-0">
            <div class="container-fluid p-0">
                <a href="#" class="burger-btn d-block">
                    <i class="bi bi-justify fs-3"></i>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-lg-0">
                        <li class="nav-item dropdown me-1">
                            <button type="button" class="btn btn-primary active dropdown-toggle me-2"
                                data-bs-toggle="dropdown">
                                <i class='bi bi-bell bi-sub fs-6'></i> Notifikasi <span class="badge bg-transparent">0</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                                <li>
                                    <h6 class="dropdown-header">Notifikasi</h6>
                                </li>
                                <li><a class="dropdown-item" href="#">Tidak ada untuk saat ini</a></li>
                            </ul>
                        </li>
                    </ul>
                    <div class="dropdown">
                        <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="user-menu d-flex">
                                <div class="user-img d-flex align-items-center">
                                    <div class="avatar avatar-lg">
                                        <img src="<?= base_url(($avatar != null) ? $avatar : '/apps/assets/images/faces/1.jpg'); ?>">
                                    </div>
                                </div>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton"
                            style="min-width: 11rem;">
                            <li>
                                <h6 class="dropdown-header">Hello, <?= $seslog['username'] ?> !</h6>
                            </li>
                            <li><a href="/profil" class="dropdown-item"><i class="icon-mid bi bi-person me-2"></i>
                                    Lihat Profil</a></li>
                            <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#change-password"
                                    style="cursor:pointer;"><i class="icon-mid bi bi-gear me-2"></i>
                                    Ubah Kata Sandi</a></li>
                            <li><a class="dropdown-item" href="#" onclick="logout()"><i
                                        class="icon-mid bi bi-box-arrow-left me-2 text-danger"></i>
                                    Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <style>
        .pintasan {
            margin: 0.75em !important;
            padding: 0px !important;
            border-radius: 1.25em !important;
            height: 65px;
        }

        .pintasan .navbar-nav {
            padding: 0px !important;
            margin: 0px !important
        }

        .pintasan .nav-link {
            font-size: 1.5em !important;
            padding: 0 !important;
            margin: 0 !important;
        }
    </style>

    <nav class="navbar bg-primary navbar-expand fixed-bottom d-md-none d-lg-none d-xl-none pintasan shadow-lg">
        <ul class="navbar-nav nav-justified w-100 p-0">
            <li class="nav-item">
                <a href="<?= base_url('/home');?>" class="nav-link">
                    <i class="bi bi-grid-fill text-white"></i>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('/pengajuan-cuti');?>" class="nav-link">
                    <i class="bi bi-bookmark-check-fill text-white"></i>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('/presensi-online');?>" class="nav-link">
                    <i class="bi bi-fingerprint text-white"></i>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('/pengajuan-izin');?>" class="nav-link">
                    <i class="bi bi-bookmark-plus-fill text-white"></i>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" onclick="logout()" class="nav-link">
                    <i class="bi bi-power text-danger"></i>
                </a>
            </li>
        </ul>
    </nav>

    <?= $this->renderSection('content'); ?>
</div>
<?= view('Apps/partials/modal/changepass'); ?>
<?= view('Apps/partials/modal/viewdetail'); ?>
<?= view('Apps/partials/footer'); ?>
<script src="<?= base_url('apps/'); ?>assets/js/custom/change-password.js?v=2'); ?>"></script>
<?= $this->renderSection('scripts'); ?>