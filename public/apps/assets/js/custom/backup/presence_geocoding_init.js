var token = localStorage.getItem('jwt_token');
const inset = $('#_in').val();
const outset = $('#_out').val();
const sessID = $('#sessid').val();

async function resizeImageFile(file, maxSize = 400, quality = 0.85) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onload = (e) => {
            const img = new Image();
            img.onload = () => {
                const canvas = document.createElement('canvas');
                let width = img.width;
                let height = img.height;

                if (width > height) {
                    if (width > maxSize) {
                        height *= maxSize / width;
                        width = maxSize;
                    }
                } else {
                    if (height > maxSize) {
                        width *= maxSize / height;
                        height = maxSize;
                    }
                }

                canvas.width = width;
                canvas.height = height;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, width, height);

                canvas.toBlob(
                    (blob) => {
                        resolve(blob);
                    },
                    'image/jpeg',
                    quality
                );
            };
            img.onerror = reject;
            img.src = e.target.result;
        };
        reader.onerror = reject;
        reader.readAsDataURL(file);
    });
}

$(document).ready(function () {

    locateUser();
    startTime();
    fetchdata();

    $('.openCameraBtn').click(function () {
        $('#cameraInput').click();
        var key = $(this).attr('data-id');
        $('#location-status').val(key);
    });

    $('#cameraInput').on('change', async function () {
        if (this.files && this.files[0]) {
            try {
                const resizedBlob = await resizeImageFile(this.files[0], 400, 0.85);
                uploadPhoto(resizedBlob);
            } catch (error) {
                console.error('Resize error:', error);
                Swal.fire({
                    toast: true,                    
                    icon: 'error',
                    title: 'Gagal memproses gambar',
                    text: 'Silakan coba lagi.'
                });
            }
        }
    });

    function uploadPhoto(file) {
        Swal.fire({
            toast: true,
            position: 'top',
            icon: 'info',
            title: 'Sedang memverifiikasi wajah ...',
            showConfirmButton: false,
            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        var formData = new FormData();
        // formData.append('image', file);
        formData.append('image', file, 'resized.jpg');

        $.ajax({
            url: "/recognize",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                Swal.close();

                if (response.error) {
                    Swal.fire({
                        toast: true,
                        position: 'top',
                        icon: 'error',
                        title: 'Perhatian ! Wajah tidak sama verifikasi gagal',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    return;
                } else {

                    if (response.results[0] == 'Unknown') {
                        Swal.fire({
                            toast: true,
                            position: 'top',
                            icon: 'warning',
                            title: 'Perhatian ! Wajah tidak ditemukan, coba kembali',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        return;
                    } else {
                        if (response.results[0] != sessID) {

                            Swal.fire({
                                toast: true,
                                position: 'top',
                                icon: 'warning',
                                title: 'Perhatian ! Wajah tidak sama',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            return;
                        } else {
                            Swal.fire({
                                toast: true,
                                position: 'top',
                                icon: 'success',
                                title: 'Verifikasi Berhasil',
                                timer: 1000,
                                showConfirmButton: false
                            }).then(() => {
                                $('#presensi').modal('show');
                            });
                        }

                    }
                }
            },
            error: function (xhr, status, error) {
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Gagal terhubung ke server: ' + error
                });
            }
        });
    }

    function timeToSeconds(timeStr) {
        const [hours, minutes, seconds] = timeStr.split(':').map(Number);
        return (hours * 3600) + (minutes * 60) + (seconds || 0);
    }

    function startTime() {

        let now = new Date(new Date().toLocaleString("en-US", {
            timeZone: "Asia/Jakarta"
        }));

        let monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus",
            "September", "Oktober", "November", "Desember"
        ];
        let d = now.getDate();
        let mo = monthNames[now.getMonth()];
        let y = now.getFullYear();
        let h = now.getHours();
        let m = now.getMinutes();
        let s = now.getSeconds();

        h = checkTime(h);
        m = checkTime(m);
        s = checkTime(s);

        const nowInSeconds = now.getHours() * 3600 + now.getMinutes() * 60 + now.getSeconds();

        var insts = $('#_sin').val();
        var outsts = $('#_sout').val();

        function secondsToTime(seconds) {
            const hours = Math.floor(seconds / 3600);
            const minutes = Math.floor((seconds % 3600) / 60);
            const hStr = hours.toString().padStart(2, '0');
            const mStr = minutes.toString().padStart(2, '0');
            return `${hStr}:${mStr}`;
        }

        const inSeconds = timeToSeconds(inset);
        const outSeconds = timeToSeconds(outset);

        const inTime = secondsToTime(inSeconds);
        const outTime = secondsToTime(outSeconds);

        $('.d-worktime').html(`${inTime} s.d ${outTime}`);

        // if (nowInSeconds <= inSeconds && insts === 'T') {
        //     $('.check-in').attr('disabled', false);
        // } else {
        //     $('.check-in').attr('disabled', true);
        // }

        // if (nowInSeconds >= outSeconds) {
        //     $('.check-out').attr('disabled', false);
        // } else {
        //     $('.check-out').attr('disabled', true);
        // }

        $('.d-date').html(d + ' <b>' + mo + '</b> ' + y);
        $('.clock .d-time hour').html(h);
        $('.clock .d-time minute').html(m);

        setTimeout(startTime, 1000);
    }

    function checkTime(i) {
        return i < 10 ? "0" + i : i;
    }

    $('.slide-submit').slideToSubmit();
    let checkSlideSuccess = setInterval(function () {
        if ($('.slide-submit').hasClass('slide-success')) {
            clearInterval(checkSlideSuccess);
            $('#form-presence').submit();
        }
    }, 100);

    $('#form-presence').on('submit', function (event) {
        event.preventDefault();
        var $form = $(this);
        var formData = new FormData($form[0]);
        $.ajax({
            type: "POST",
            headers: {
                'Authorization': 'Bearer ' + token
            },
            url: "/ajxpresencesubmit",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                Swal.fire({
                    toast: true,
                    position: 'top',
                    icon: response.status,
                    title: response.message,
                    timer: 1000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            }
        });
    });
});

// $(document).on('click', '.btn-presence', function (e) {
//     var key = $(this).attr('data-id');
//     $('#location-status').val(key);
// });

function timeToSeconds(timeStr) {
    const [hours, minutes, seconds] = timeStr.split(':').map(Number);
    return (hours * 3600) + (minutes * 60) + (seconds || 0);
}

function fetchdata() {
    $.ajax({
        url: "/fetchdata",
        dataType: 'JSON',
        headers: {
            'Authorization': 'Bearer ' + token
        },
        success: function (data) {
            // console.log(data);
            $('#_sin').val(data.start_flag);
            $('#_sout').val(data.end_flag);
            $('#swpin').html(data.start_time);
            $('#swpout').html(data.end_time);

            if (data.avatar == null) {
                Swal.fire({
                    title: 'Warning',
                    text: 'Harap lakukan registrasi wajah dan lengkapi profil terlebih dahulu',
                    icon: 'warning',
                    confirmButtonColor: '#364b98',
                    confirmButtonText: 'OK',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: true
                }).then(() => {
                    window.location.href = '/profil';
                });
            }

            // if (data.start_flag === 'F') {
            //     $('.check-in').attr('disabled', true);
            // }

            // if (data.end_flag === 'F') {
            //     $('.check-out').attr('disabled', true);
            // }
        },
        error: function (xhr, status, error) {
            console.error('Gagal mengambil data:', error);
        }
    });
}

var map = L.map("map-layer", {
    zoomControl: false,
    scrollWheelZoom: false,
    doubleClickZoom: false,
    touchZoom: false,
    dragging: false
}).setView([-6.258841951363996, 106.86863948042689], 15);
L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution: '',
}).addTo(map);

function locateUser() {
    if (!navigator.geolocation) {
        alert("Geolocation tidak didukung oleh browser Anda.");
        return;
    }

    const options = {
        enableHighAccuracy: true,
        timeout: 15000,
        maximumAge: 0
    };

    navigator.geolocation.getCurrentPosition(
        successCallback,
        errorCallback,
        options
    );

    function successCallback(position) {
        const lat = position.coords.latitude;
        const lng = position.coords.longitude;
        const acu = position.coords.accuracy;

        // console.log(`Latitude: ${lat}, Longitude: ${lng}, Accuracy: ${acu}m`);

        // if (acu > 1000) {
        //     Swal.fire({
        //         toast: true,
        //         position: 'top',
        //         icon: 'warning',
        //         title: 'Lokasi Anda tidak akurat (> 1000m). Silakan aktifkan GPS atau matikan Fake GPS.',
        //         timer: 3000,
        //         showConfirmButton: false
        //     });
        // }

        // Cek IP fallback lokasi (kalau mau)
        // checkIPLocation(lat, lng);

        const offsetLng = lng - 0.003;
        const offsetLat = lat + 0.001;
        const ColorIcon = L.Icon.extend({
            options: {
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
                iconSize: [35, 35],
                shadowSize: [60, 37],
                iconAnchor: [15, 40],
                popupAnchor: [2, -40]
            }
        });

        const greenIcon = new ColorIcon({
            iconUrl: 'https://cdn-icons-png.flaticon.com/512/8587/8587894.png'
        });

        const marker = L.marker([lat, lng], {
                icon: greenIcon
            })
            .addTo(map)
            .bindPopup(`Lokasi Anda<br>Latitude: ${lat}<br>Longitude: ${lng}`)
            .openPopup();

        const circle = L.circle([lat, lng], {
            radius: Math.max(acu, 75)
        });

        const featgp = L.featureGroup([marker, circle]).addTo(map);
        map.fitBounds(featgp.getBounds());
        map.setView([offsetLat, offsetLng], 16);

        const api_key = localStorage.getItem("locationiq_key");
        const api_url = `https://us1.locationiq.com/v1/reverse.php?key=${api_key}&lat=${lat}&lon=${lng}&format=json`;
        $.getJSON(api_url, function (data) {
            const address = `${data.address.village || ''}, ${data.address.subdistrict || ''}, ${data.address.city || ''}, ${data.address.state || ''}, ${data.address.country}`;
            marker.setPopupContent(`<b>Lokasi Anda</b>:Koor: ${data.lat},<br> ${data.lon}`).openPopup();

            $('.coord').html(`<small class='text-soft'>${data.lat},<br>${data.lon}</small>`);

            // Presensi zone check
            const coor_regis = {
                lat: -6.898974404825013,
                lng: 107.62263754049694
            };
            const coor_radius = 200; // meters
            const inZone = arePointsNear({
                lat: data.lat,
                lng: data.lon
            }, coor_regis, coor_radius);

            if (inZone) {
                $('.geo-alert').html(`<div class="alert alert-success alert-dismissible show fade rounded">
                    <em class="icon ni ni-check-circle"></em>
                    <strong>GeoLocation</strong>. Anda berada di dalam zona presensi
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`);
                $('.check-in, .check-out').attr('disabled', false);
            } else {
                $('.geo-alert').html(`<div class="alert alert-warning alert-dismissible show fade rounded">
                    <em class="icon ni ni-alert-circle"></em>
                    <strong>GeoLocation</strong>. Anda berada di luar zona presensi
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`);
                // $('.check-in, .check-out').attr('disabled', true);
            }
        });
    }

    function errorCallback(error) {
        console.error("Geolocation error:", error);
        alert("Gagal mendapatkan lokasi: " + error.message);
    }

    function arePointsNear(checkPoint, centerPoint, m) {
        const ky = 40000 / 360;
        const kx = Math.cos(Math.PI * centerPoint.lat / 180.0) * ky;
        const dx = Math.abs(centerPoint.lng - checkPoint.lng) * kx;
        const dy = Math.abs(centerPoint.lat - checkPoint.lat) * ky;
        return Math.sqrt(dx * dx + dy * dy) <= m / 1000;
    }
}

function CurrentlocateUser() {
    navigator.geolocation.getCurrentPosition(
        function (position) {
            var lat = position.coords.latitude;
            var lng = position.coords.longitude;
            var offsetlat = lat - (-0.0002);

            // checkIPLocation(lat, lng);
            let clr = L.Icon.extend({
                options: {
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
                    iconSize: [35, 35],
                    shadowSize: [60, 37],
                    iconAnchor: [15, 40],
                    popupAnchor: [2, -40]
                }
            });

            let CustomIcon = new clr({
                iconUrl: 'https://cdn-icons-png.flaticon.com/512/8587/8587894.png'
            });
            var mapInstancemarker = L.marker([lat, lng], {
                    icon: CustomIcon
                }).addTo(mapInstance)
                .bindPopup("Lokasi Anda<br>Latitude: " + lat + "<br>Longitude: " + lng)
                .openPopup();
            mapInstance.setView([offsetlat, lng], 20);
            var api_key = localStorage.getItem("locationiq_key");
            var api_url =
                `https://us1.locationiq.com/v1/reverse.php?key=${api_key}&lat=${lat}&lon=${lng}&format=json`;

            $.ajax({
                type: "GET",
                url: api_url,
                dataType: "JSON",
                cache: false,
                success: function (data) {
                    mapInstancemarker.setPopupContent("<b>Lokasi Anda</b> : <br>" + data.lat + ",<br>" + data.lon)
                        .openPopup();
                    $('#location-latitude').val(data.lat);
                    $('#location-longitude').val(data.lon);
                    $('#location-fulladdress').val(data.display_name);
                    $('#location-city').val(data.address.city);
                    $('#location-country-code').val(data.address.country_code);
                    $('#location-postcode').val(data.address.postcode);
                    $('#location-state').val(data.address.state);
                    $('#location-road').val(data.address.road);
                    $('#location-country').val(data.address.country);
                }
            });

        },
        function (error) {
            alert("Gagal mendapatkan lokasi: " + error.message);
        }
    );
}

// function checkIPLocation(lat, lng) {
//     $.get("https://ip-api.com/json", function (data) {
//         var ipLat = data.lat;
//         var ipLon = data.lon;
//         var distance = getDistanceFromLatLonInKm(ipLat, ipLon, lat, lng);
//         if (distance > 50) {
//             Swal.fire({
//                 toast: true,
//                 position: 'top',
//                 icon: 'warning',
//                 title: 'Perhatian ! Harap matikan Fake GPS.',
//                 timer: 1000,
//                 showConfirmButton: false
//             }).then(() => {
//                 window.location.href = "/home";
//             });
//             return;
//         }
//     });
// }

function getDistanceFromLatLonInKm(lat1, lon1, lat2, lon2) {
    var R = 6371; // Radius bumi dalam km
    var dLat = deg2rad(lat2 - lat1);
    var dLon = deg2rad(lon2 - lon1);
    var a =
        Math.sin(dLat / 2) * Math.sin(dLat / 2) +
        Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
        Math.sin(dLon / 2) * Math.sin(dLon / 2);
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    var d = R * c; // Jarak dalam km
    return d;
}

function deg2rad(deg) {
    return deg * (Math.PI / 180);
}