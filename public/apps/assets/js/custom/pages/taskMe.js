loadData();

async function loadData(keyword = '') {
    const response = await fetch('/fetch-layanan-enrolled', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        }
    });
    const data = await response.json();
    pageLoaded(data);
}

function pageLoaded(data) {
    $('#loaded').html('');
    $('#spinLoadData').addClass('d-none');
    if (Array.isArray(data.list) && data.list.length === 0) {
        var card = `
        <div class="col-md-12">
            <div class="d-flex justify-content-center align-items-center" style="height:500px;">
                <div class="text-center">
                    <div style="width: 100%;">
                        <iframe
                            src="https://lottie.host/embed/32e72f58-9db0-476b-895e-185c0566d08f/Klshx3kbL6.lottie"
                            style="width: 100%; height: 300px; border: none;" allowfullscreen>
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
        `;
        $('#loaded').append(card);
    } else {
        $.each(data.list, function (index, value) {
            var card = `
                <div class="col-12 col-md-3">
                    <div class="card text-center h-100 ${value.status == 1 ? 'card-hover-border card-daily-task' : 'deactivated-card'}" data-key="${value.id}" data-alias="${value.alias}" data-code="${value.code}">
                        <div class="card-body card-body-task" style="cursor:pointer;">
                            <span class="badge badge-position-top-end ${value.status == 1 ? 'bg-light-primary' : 'bg-light-secondary'}">${value.status == 1 ? 'active' : 'deactive'}</span>
                            <i class="bi bi-folder-check fs-1 text-primary mb-2"></i>
                            <h5 class="fw-bold">${value.alias}</h5>
                            <p class="text-muted small mb-0 mt-auto d-none d-md-block">
                                Layanan Kanreg III BKN Bandung
                            </p>
                        </div>
                    </div>
                </div>
            `;
            $('#loaded').append(card);
        });
    }

    $('.card-daily-task').on('click', function () {
        var code = $(this).attr('data-code');
        var urlupload = '/upload-' + code;
        var urlentry = '/entry-' + code;
        var urlinfo = '/info-' + code;

        $('.card-upload').on('click', function () {
            window.location.href = urlupload;
        })

        $('.card-entry').on('click', function () {
            window.location.href = urlentry;
        })

        $('.card-info').on('click', function () {
            window.location.href = urlinfo;
        })

        $('#quickActionModal').modal('show');
    });
}

function swlErrorHandler(msg) {
    Swal.fire({
        toast: true,
        position: 'top',
        icon: 'error',
        title: msg,
        timer: 3000,
        showConfirmButton: false
    });
}

function swlSuccess() {
    Swal.fire({
        toast: true,
        position: 'top',
        icon: 'success',
        title: 'Data berhasil di simpan',
        timer: 3000,
        showConfirmButton: false
    }).then(() => {
        window.location.reload();
    });
}

function swlwaitProsessing() {
    Swal.fire({
        toast: true,
        position: 'top',
        icon: 'info',
        title: 'Permintaan sedang di proses ...',
        showConfirmButton: false,
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading();
        }
    });
}