<div class="modal fade" role="dialog" id="view-modal">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
            <div class="modal-body modal-body-xl m-4">
                <h5 class="title">Detail Pengajuan</h5>
                <p>Berikut merupakan detail dari pengajuan izin/cuti</p>
                <form id="formtrx">
                    <div class="row g-2">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-label">Kode Dokumen</label>
                                <div class="form-control-wrap">
                                    <input type="text" readonly class="form-control code">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label">User ID</label>
                                <div class="form-control-wrap">
                                    <input type="text" readonly class="form-control userid">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label">Nama Pegawai</label>
                                <div class="form-control-wrap">
                                    <input type="text" readonly class="form-control name">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label">Jenis Pengajuan</label>
                                <div class="form-control-wrap">
                                    <input type="text" readonly class="form-control group">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label">Jenis Izin</label>
                                <div class="form-control-wrap">
                                    <input type="text" readonly class="form-control category">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label">Tanggal Mulai</label>
                                <div class="form-control-wrap">
                                    <input type="text" readonly class="form-control start">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label">Tanggal Selesai</label>
                                <div class="form-control-wrap">
                                    <input type="text" readonly class="form-control end">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label">Jam Koreksi</label>
                                <div class="form-control-wrap">
                                    <input type="text" readonly class="form-control time">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label">Jumlah Cuti</label>
                                <div class="form-control-wrap">
                                    <input type="text" readonly class="form-control instance">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-label">Alasan</label>
                                <div class="form-control-wrap">
                                    <textarea class="form-control reason" rows="3" readonly></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-label" for="customFileLabel">Lampiran</label> : <strong
                                    class="attach"></strong>
                            </div>
                        </div>
                    </div>
                    <div class="row gy-4">
                        <div class="col-12">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <span class="">Batalkan</span>
                            </button>
                            <button type="button" class="btn btn-danger btn-approve" data-status="4">
                                <span class="">Tolak Pengajuan</span>
                            </button>
                            <button type="button" class="btn btn-primary ml-1 btn-approve" data-status="3">
                                <span class="">Setujui Pengajuan</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>