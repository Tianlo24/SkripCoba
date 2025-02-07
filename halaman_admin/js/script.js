let geocoder;
let map;
let zonePolygons = {};

function initMap() {
    geocoder = new google.maps.Geocoder();
    const palangkaRaya = { lat: -2.2110, lng: 113.9213 }; // Koordinat Palangka Raya
    map = new google.maps.Map(document.getElementById("map"), {
        center: palangkaRaya,
        zoom: 12,
    });

    document.getElementById("map").style.display = "block";

    // Definisikan koordinat untuk setiap zona
    const zones = {
        zone1: [
            { lat: -2.211470761295292, lng: 113.90947611669986 }, 
            { lat: -2.2075065012204456, lng: 113.91633705569276 },
            { lat: -2.214548885426217, lng: 113.9204442844324 },
            { lat: -2.21854421870365, lng: 113.91348999932791 },
        ],
        zone2: [
            { lat: -2.2177312949398704, lng: 113.89814552517936 },
            { lat: -2.224697420083387, lng:  113.90296281562146 },
            { lat: -2.2185531427986414, lng: 113.91349819059735 },
            { lat: -2.2114730032254766, lng: 113.90949159495867 },
        ],
        // Tambahkan zona lainnya sesuai kebutuhan
    };

    Object.keys(zones).forEach(zoneName => {
        const polygon = new google.maps.Polygon({
            paths: zones[zoneName],
            strokeColor: "#FF0000",
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: "#FF0000",
            fillOpacity: 0.35,
        });
        polygon.setMap(map);
        zonePolygons[zoneName] = polygon;
    });
}

let globalLatitude = null;
let globalLongitude = null;

function geocodeAddress() {
    const address = document.getElementById('addressInput').value; // Ambil alamat dari input
    geocoder.geocode({ 'address': address }, (results, status) => {
        if (status === 'OK') {
            globalLatitude = results[0].geometry.location.lat();
            globalLongitude = results[0].geometry.location.lng();
            
            // Masukkan latitude dan longitude ke dalam input field
            document.getElementById('latitudeInput').value = globalLatitude;
            document.getElementById('longitudeInput').value = globalLongitude;

            // Panggil fungsi lain dengan latitude dan longitude
            useCoordinates(globalLatitude, globalLongitude);
        } else {
            alert('Geocode was not successful for the following reason: ' + status);
        }
    });
}


// Menambahkan event listener untuk memanggil initMap saat DOM siap
document.addEventListener('DOMContentLoaded', (event) => {
    initMap();
});


// Fungsi untuk memeriksa zona
function checkPackageZone(lat, lng) {
    const pointToCheck = new google.maps.LatLng(lat, lng);
    let foundZone = null;

    // Cek setiap zona
    Object.keys(zonePolygons).forEach(zoneName => {
        if (google.maps.geometry.poly.containsLocation(pointToCheck, zonePolygons[zoneName])) {
            foundZone = zoneName;
        }
    });

    // Tampilkan hasil di form
    const zoneInput = document.getElementById("zona");
    if (foundZone) {
        zoneInput.value = `${foundZone}`;
    } else {
        zoneInput.value = 'Paket tidak berada dalam zona yang ditentukan.';
    }
}