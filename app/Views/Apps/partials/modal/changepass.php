<div class="modal fade" role="dialog" id="change-password">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
            <div class="modal-body modal-body-xl m-4">
                <h5 class="title">Perbaharui Kata Sandi</h5>
                <p>Pengaturan ini membantu Anda menjaga keamanan akun Anda.</p>
                <form method="POST" id="changePasswordForm">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <a tabindex="-1" href="#" class="form-icon form-icon-right passcode-switch lg"
                                data-target="password1">
                                <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                            </a>
                            <input type="password" class="form-control form-control-lg rounded" name="o_password1"
                                id="password1" required placeholder="Kata sandi lama" autocomplete="new-password">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <a tabindex="-1" href="#" class="form-icon form-icon-right passcode-switch lg"
                                data-target="password2">
                                <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                            </a>
                            <input type="password" class="form-control form-control-lg rounded" name="o_password2"
                                id="password2" required placeholder="Kata sandi baru" autocomplete="new-password">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <a tabindex="-1" href="#" class="form-icon form-icon-right passcode-switch lg"
                                data-target="password3">
                                <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                            </a>
                            <input type="password" class="form-control form-control-lg rounded" name="o_password3"
                                id="password3" required placeholder="Masukan ulang kata sandi"
                                autocomplete="new-password">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary ml-1 btn-form-submit">Ubah Kata Sandi</button>
            </div>
        </div>
    </div>
</div>