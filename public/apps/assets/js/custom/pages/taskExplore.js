loadData();

async function loadData(keyword = '') {
    var unit = $('#unit').val();
    const response = await fetch('/fetch-layanan', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            keyword: keyword,
            unit: unit,
        })
    });
    const data = await response.json();
    pageLoaded(data);
}

$('.bidang').on('click', function () {
    unit = $(this).attr('data-key');
    $('#unit').val(unit);
    loadData();
});

$('#searchdata').on('input', function () {
    const keyword = $(this).val();
    loadData(keyword);
});

$('#searchdata').on('keypress', function (e) {
    if (e.which === 13) {
        const keyword = $(this).val();
        loadData(keyword);
    }
});

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
                    <div class="card text-center h-100 card-hover-border card-task" data-key="${value.id}" data-alias="${value.alias}">
                        <div class="card-body" style="cursor:pointer;">
                            <i class="bi bi-folder-plus fs-1 text-primary mb-2"></i>
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

    $('.card-task').on('click', function () {
        var keydata = $(this).attr('data-key');
        var aliases = $(this).attr('data-alias');
        var key = $(this).attr('data-key');
        Swal.fire({
            title: "Konfirmasi",
            text: "Apakah kamu yakin akan enroll " + aliases + " ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#dc3545",
            cancelButtonColor: "#435ebe",
            confirmButtonText: "Ya, Saya akan ambil",
            cancelButtonText: "Batalkan"
        }).then((result) => {
            if (result.isConfirmed) {
                swlwaitProsessing();
                enrollTask(keydata);
            }
        });
    });
}

function enrollTask(key) {
    fetch('/store-enroll', {
            method: 'POST',
            body: JSON.stringify({
                enrolled: key
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({
                    toast: true,
                    position: 'top',
                    icon: 'success',
                    title: data.message,
                    timer: 3000,
                    showConfirmButton: false
                });
            } else {
                let errorMessage = data.message;
                if (typeof errorMessage === 'object') {
                    errorMessage = Object.values(errorMessage).join('<br>');
                }
                swlErrorHandler(errorMessage);
            }
        })
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