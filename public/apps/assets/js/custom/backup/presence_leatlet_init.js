let mapInstance;
$(document).on('shown.bs.modal', '#presensi', function () {
    if (!mapInstance) {
        mapInstance = L.map("map-layer-modal", {
            zoomControl: false,
            scrollWheelZoom: false,
            doubleClickZoom: false,
            touchZoom: false,
            dragging: false
        }).setView([-6.258841951363996, 106.86863948042689], 10);
        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            attribution: "&copy; OpenStreetMap contributors"
        }).addTo(mapInstance);
    } else {
        setTimeout(() => {
            mapInstance.invalidateSize();
        }, 300);
    }
    CurrentlocateUser();
});

$(document).on('hidden.bs.modal', '#presensi', function () {
    locateUser();
});