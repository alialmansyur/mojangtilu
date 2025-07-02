<?= view('Apps/partials/header'); ?>
<?= $this->renderSection('style'); ?>
<?= view('Apps/partials/sidebar'); ?>
<div id="main" class="mt-0 p-4">
    <header class="mb-3">
        <a href="#" class="burger-btn d-block d-xl-none">
            <i class="bi bi-justify fs-3"></i>
        </a>
    </header>
    <?= $this->renderSection('content'); ?>
</div>
<?= view('Apps/partials/modal/changepass'); ?>
<?= view('Apps/partials/footer'); ?>
<script src="<?= base_url('apps/'); ?>assets/js/custom/change-password.js?v=2'); ?>"></script>
<?= $this->renderSection('scripts'); ?>