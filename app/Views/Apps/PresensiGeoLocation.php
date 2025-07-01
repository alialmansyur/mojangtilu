<?= $this->extend('Apps/layouts/main_layout'); ?>
<?= $this->section('content'); ?>

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
    <input type="hidden" id="sessid" value="<?= $seslog['username'] ?>">
    <input type="hidden" id="_sts" value="<?= $setuplist[0]['setting_value'] ?>">
    <input type="hidden" id="_in" value="<?= $setuplist[1]['setting_value'] ?>">
    <input type="hidden" id="_out" value="<?= $setuplist[2]['setting_value'] ?>">
    <input type="hidden" id="_sin">
    <input type="hidden" id="_sout">
    <form id="cameraForm" class="d-none" enctype="multipart/form-data">
        <input type="file" class="d-none" name="image" id="cameraInput" accept="image/*" style="display:none;"
            required capture="user">
    </form>
    <section class="row g-gs justify-content-center">
        <div class="col-lg-12">
            <div class="geo-alert"></div>
            <div class="presence-time"></div>
            <div class="card bg-primary text-white mb-3 shadow-sm bg-pattern">
                <div class="card-body py-4 px-4">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-xl">
                            <img src="<?= base_url(($avatar != null) ? $avatar : '/apps/assets/images/faces/1.jpg'); ?>">
                        </div>
                        <div class="ms-3 name text-white">
                            <h5 class="font-bold text-white"><?= $seslog['fullname'] ?></h5>
                            <h6 class="text-muted mb-0 text-white"><?= $seslog['username'] ?></h6>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-4">
                        <div class="flex-fill text-center p-2 rounded-3 bg-primary-subtle text-primary-emphasis">
                            <small class="d-block fw-bold">Absen Datang</small>
                            <div class="fw-semibold" id="swpin">...</div>
                        </div>
                        <div class="flex-fill text-center p-2 rounded-3 bg-primary-subtle text-primary-emphasis">
                            <small class="d-block fw-bold">Absen Pulang</small>
                            <div class="fw-semibold" id="swpout">...</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row g-gs">
                        <div class="col-lg-3 position-absolute top-0 start-0 p-4 m-4 card-container"
                            style="z-index: 999;" data-aos="zoom-out">
                            <div class="card shadow-sm h-100">
                                <div class="card-body p-4 text-center d-flex flex-column align-items-center"
                                    style="min-height: 340px;">
                                    <h5 class="m-0 mt-2">Live Attendance</h5>
                                    <h1 class="m-0 clock">
                                        <span class="d-time">
                                            <hour>00</hour>
                                            <delimit>:</delimit>
                                            <minute>00</minute>
                                        </span>
                                    </h1>
                                    <p class="m-0"><span class="d-date">...</span></p>
                                    <p class="m-0"><b>Normal</b><br><span class="d-worktime">07:00 s.d 16.00</span></p>
                                    <smalll class="coord text-muted">Koordinat : ...</smalll>
                                </div>
                                <div class="d-flex w-100 border-top bg-light p-2 justify-content-between"
                                    style="position: absolute; bottom: 0; left: 0; border-radius:0 0 1.25em 1.25em">
                                    <button class="btn btn-xl btn-primary w-100 me-1 check-in openCameraBtn"
                                        data-id="1">
                                        <strong>IN</strong>
                                    </button>
                                    <button class="btn btn-xl btn-danger w-100 ms-1 check-out openCameraBtn"
                                        data-id="2">
                                        <strong>OUT</strong>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 d-none d-md-block">
                            <div class="rounded p-0 m-0" id="map-layer"></div>
                        </div>
                    </div>
                </div>
            </div>
            <?php if($datalist): ?>
            <div class="card">
                <div class="card-body">
                    <p>Data di temukan sebanyak : <strong><?= count($datalist); ?> Data</strong></p>
                    <div class="table-responsive">
                        <table id="exportTable" class="table table-hover table-bordered nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>NIP</th>
                                    <th>Tanggal</th>
                                    <th>Absen In</th>
                                    <th>Absen Out</th>
                                    <th>Total Jam</th>
                                    <th>Total Menit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($datalist as $key => $value): ?>
                                <tr>
                                    <td><?= $key+1; ?></td>
                                    <td><?= $value['username']; ?></td>
                                    <td><?= date('d F Y',strtotime($value['presence_date'])); ?></td>
                                    <td><?= ($value['start_time'] != null) ? $value['start_time'] : "-"; ?></td>
                                    <td><?= ($value['end_time'] != null) ? $value['end_time'] : "-"; ?></td>
                                    </td>
                                    <td><?= ($value['work_time_hour']) ? $value['work_time_hour'] : 0 ; ?> Jam</td>
                                    <td><?= ($value['work_time_minutes']) ? $value['work_time_minutes'] : 0 ; ?>
                                        Menit</td>
                                </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                        </table>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div class="card card-bordered border-primary d-flex justify-content-center align-items-center vh-100">
                <div class="card-inner card-inner-lg ">
                    <div class="text-center">
                        <div style="width: 100%;">
                            <iframe
                                src="https://lottie.host/embed/32e72f58-9db0-476b-895e-185c0566d08f/Klshx3kbL6.lottie"
                                style="width: 100%; height: 300px; border: none;" allowfullscreen>
                            </iframe>
                        </div>
                        <div class="mx-auto mt-4 p-4">
                            <h4><b>No data</b></h4>
                            <p>Belum ada data untuk saat ini</p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </section>
</div>

<div class="modal fade" role="dialog" id="presensi">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
            <div class="modal-body modal-body-xl m-2">
                <h4 class=""><strong>Live Attendance</strong></h4>
                <div class="row gy-4">
                    <div class="col-12">
                        <div class="rounded mb-2" id="map-layer-modal"></div>
                        <form id="form-presence">
                            <div id="location-content d-none">
                                <input type="hidden" id="location-latitude" name="latitude">
                                <input type="hidden" id="location-longitude" name="longitude">
                                <input type="hidden" id="location-fulladdress" name="address">
                                <input type="hidden" id="location-city" name="city">
                                <input type="hidden" id="location-country-code" name="country_code">
                                <input type="hidden" id="location-state" name="state">
                                <input type="hidden" id="location-postcode" name="postcode">
                                <input type="hidden" id="location-road" name="road">
                                <input type="hidden" id="location-country" name="country">
                                <input type="hidden" id="location-status" name="status">
                            </div>
                            <div class="slide-submit">
                                <div class="slide-submit-text">Slide To Submit</div>
                                <div class="slide-submit-thumb bg-primary"><em class="icon bi bi-arrow-right"></em>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
<?= $this->section('scripts'); ?>
<script src="<?= base_url('apps/assets/js/aos-anim/aos.js'); ?>"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="<?= base_url('apps/assets/js/slide-form/js/slide-to-submit.js'); ?>"></script>
<script src="<?= base_url('apps/assets/js/custom/presence_geocoding_init.js?v=5.5.1'); ?>"></script>
<script src="<?= base_url('apps/assets/js/custom/presence_leatlet_init.js?v=2'); ?>"></script>
<script>
    AOS.init();
</script>
<?= $this->endSection(); ?>