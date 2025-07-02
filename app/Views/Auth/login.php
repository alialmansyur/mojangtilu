<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Mojang Tilu | Kanreg Tilu</title>
    <link rel="shortcut icon" href="<?= base_url('apps/');?>assets/images/logo/favicon.png?v=2" type="image/x-icon">
    <link rel="shortcut icon" href="<?= base_url('apps/');?>assets/images/logo/favicon.png?v=2" type="image/png">
    <link rel="stylesheet" href="<?= base_url('apps/');?>assets/css/main/app.css?v=2">
    <link rel="stylesheet" href="<?= base_url('apps/');?>assets/css/pages/auth.css?v=2">
    <link rel="stylesheet" href="<?= base_url('apps/');?>assets/extensions/toastify-js/src/toastify.css">
    <script src="<?= base_url('apps/'); ?>assets/extensions/jquery/jquery.min.js"></script>
    <script src="<?= base_url('apps/'); ?>assets/extensions/toastify-js/src/toastify.js"></script>
    <script src="<?= base_url('apps/'); ?>assets/js/custom/authprocess.js?v=4.0"></script>
    <style>
        .rounded{
            border-radius: 0.95em !important; 
        }
    </style>
</head>

<body> 
    <div id="auth">
        <div class="row h-100">
            <div class="col-lg-5 col-12">
                <div id="auth-left">
                    <div class="auth-logo">
                        <a href="#"><img src="<?= base_url('apps/');?>assets/images/logo/logo.png" alt="Logo"></a>
                    </div>
                    <h1 class="auth-title mb-2">MojangTilu</h1>
                    <p class="auth-subtitle mt-0 mb-4">Log in using credentials</p>
                    <div style="height:25px;"></div>
                    <form id="loginForm">
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="text" class="form-control form-control-lg rounded" name="o_userlogin" required
                                placeholder="Email / Username" autocomplete="off">
                            <div class="form-control-icon">
                                <i class="bi bi-person"></i>
                            </div>
                        </div>
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="password" class="form-control form-control-lg rounded" id="passwordnew"
                                name="o_password" required placeholder="Passcode" autocomplete="new-password">
                            <div class="form-control-icon">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                        </div>
                        <div class="form-check form-check-lg d-flex align-items-end">
                            <input class="form-check-input me-2" type="checkbox" value="" id="flexCheckDefault">
                            <label class="form-check-label text-gray-600" for="flexCheckDefault">
                                Keep me logged in
                            </label>
                        </div>
                        <button class="btn btn-primary btn-block btn-lg mt-4 rounded">Log in</button>
                        <div class="text-center mt-3"><small class="font-semibold text-muted">Version 2.0</small></div>
                    </form>
                </div>
            </div>
            <div class="col-lg-7 d-none d-lg-block position-relative">
                <div id="auth-right"></div>
            </div>
        </div>
    </div>
</body>
</html>