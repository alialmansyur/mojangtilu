<?= $this->extend('Apps/layouts/main_layout'); ?>
<?= $this->section('content'); ?>
<div class="page-heading">
    <div class="card shadow-sm">
        <div class="card-body p-2 px-4">
            <div class="row align-items-center d-flex justify-content-between">
                <div class="col-md-6 text-start">
                    <h3 class="mt-3"><?= $title; ?></h3>
                    <p class="text-subtitle text-muted">Portal Pegasus Future System</p>
                </div>
                <div class="col-md-6 text-end">
                    <button class="btn btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#collapseExample"
                        aria-expanded="false"><i class="bi bi-filter"></i> Filter
                        Data</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="page-content">
    <section class="row">
        <div class="col-md-12">
            <div class="card card-bordered border-primary d-flex justify-content-center align-items-center vh-100">
                <div class="card-inner card-inner-lg ">
                    <div class="text-center">
                        <div style="width: 100%;">
                            <iframe
                                src="https://lottie.host/embed/14ab0cbc-84f1-481c-836f-893f4f4257d8/EJT3Jgo2hi.lottie"
                                style="width: 100%; height: 300px; border: none;" allowfullscreen>
                            </iframe>
                        </div>
                        <div class="mx-auto mt-4">
                            <h4><b>Comming soon<br>This page under contruction</b></h4>
                            <p>Ga sabar yah, nampaknya halaman ini belum tersedia,<br>kamu boleh kembali lagi lain kali yah.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection(); ?>