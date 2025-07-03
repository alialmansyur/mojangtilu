const inputElement = document.querySelector('.basic-filepond');
const pond = FilePond.create(inputElement);

FilePond.setOptions({
    server: {
        process: (fieldName, file, metadata, load, error, progress, abort) => {
            swlwaitProsessing();
            const formData = new FormData();
            formData.append(fieldName, file, file.name);
            const request = new XMLHttpRequest();
            request.open('POST', '/store/master-data');
            request.upload.onprogress = (e) => {
                progress(e.lengthComputable, e.loaded, e.total);
            };
            request.onload = function () {
                if (request.status >= 200 && request.status < 300) {
                    const response = JSON.parse(request.responseText);
                    load(request.responseText);
                    swlSuccess();
                } else {
                    swlErrorHandler("Opps Terjadi kesalahan ! " + request.responseText)
                }
            };
            request.send(formData);
            return {
                abort: () => {
                    request.abort();
                    abort();
                }
            };
        }
    },
    labelIdle: 'Drag & Drop file Excel atau <span class="filepond--label-action">Browse</span>',
    labelFileTypeNotAllowed: 'File hanya boleh Excel (.xls, .xlsx)',
    acceptedFileTypes: [
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    ],
    fileValidateTypeLabelExpectedTypes: 'Hanya file Excel (.xls, .xlsx) yang diperbolehkan',
    credits: false,
    fileValidateTypeDetectType: (source, type) => new Promise((resolve) => resolve(type))
});

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
        title: 'Data berhasil di simpan, halaman akan di load ulang',
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