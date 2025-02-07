<?php
session_start();
include '../koneksi.php';
include '../fungsi.php';
error_reporting();

if (!isset($_SESSION['username'])) {
    die("Anda harus login terlebih dahulu.");
}
$sesi = $_SESSION['username'];
  
?>

<!DOCTYPE html>
<html>
<head>
    <title>Express Expedition</title>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA7ROdAUadsuUA9nXde60Xm1qzAx8y46EM" async defer></script>
    <script>
        let map;
        let start; // Lokasi awal akan diisi dengan lokasi pengguna
        const goals = []; // Array untuk menyimpan tujuan
        if (navigator.geolocation) {
        navigator.geolocation.watchPosition(position => {
        const userLocation = {
            lat: position.coords.latitude,
            lng: position.coords.longitude
        };

        // Periksa akurasi
        if (position.coords.accuracy < 50) { // Misalnya, akurasi kurang dari 50 meter
            console.log("Lokasi Anda:", userLocation);
            // Lakukan sesuatu dengan userLocation, seperti menandai di peta
        } else {
            console.warn("Akurasi lokasi tidak cukup baik:", position.coords.accuracy);
        }
    }, error => {
        console.error("Error mendapatkan lokasi:", error);
        alert("Gagal mendapatkan lokasi. Pastikan pengaturan lokasi diaktifkan dan coba lagi.");
    }, {
        enableHighAccuracy: true,
        timeout: 20000,
        maximumAge: 0
    });
} else {
    console.error("Geolocation tidak didukung oleh browser ini.");
    alert("Browser Anda tidak mendukung geolocation.");
}
        function initMap() {
            // Mendapatkan lokasi pengguna
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(position => {
                    start = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };

                    // Inisialisasi peta dengan lokasi pengguna
                    map = new google.maps.Map(document.getElementById("map"), {
                        zoom: 14,
                        center: start,
                    });

                    // Menandai lokasi pengguna
                    const startMarker = new google.maps.Marker({
                        position: start,
                        map: map,
                        title: "Lokasi Anda"
                    });

                    // Menambahkan deskripsi untuk marker lokasi pengguna
                    const startInfoWindow = new google.maps.InfoWindow({
                        content: `<h4>Lokasi Anda</h4><p>Ini adalah lokasi Anda saat ini.</p>`
                    });

                    startMarker.addListener('click', () => {
                        startInfoWindow.open(map, startMarker);
                    });

                    // Menandai semua tujuan dan menambahkan deskripsi
                    goals.forEach(goal => {
                        const marker = new google.maps.Marker({
                            position: goal.location,
                            map: map,
                            title: goal.name
                        });

                        const infoWindow = new google.maps.InfoWindow({
                            content: `<h4>${goal.name}</h4><p>${goal.description}</p>`
                        });

                        marker.addListener('click', () => {
                            infoWindow.open(map, marker);
                        });
                    });
                }, () => {
                    handleLocationError(true);
                });
            } else {
                // Browser tidak mendukung Geolocation
                handleLocationError(false);
            }
        }

        function handleLocationError(browserHasGeolocation) {
            const errorMessage = browserHasGeolocation ?
                "Error: Layanan Geolocation gagal." :
                "Error: Browser Anda tidak mendukung geolocation.";
            console.error(errorMessage);
        }

        function findNearestGoal() {
            const directionsService = new google.maps.DirectionsService();
            const distances = [];

            // Menghitung jarak ke setiap tujuan
            goals.forEach(goal => {
                const request = {
                    origin: start,
                    destination: goal.location,
                    travelMode: google.maps.TravelMode.DRIVING,
                };

                directionsService.route(request, (result, status) => {
                    if (status === google.maps.DirectionsStatus.OK) {
                        const distance = result.routes[0].legs[0].distance.value; // Jarak dalam meter
                        distances.push({ name: goal.name, location: goal.location, distance: distance });

                        // Jika semua rute sudah dihitung, cari tujuan terdekat
                        if (distances.length === goals.length) {
                            const nearestGoal = distances.reduce((prev, curr) => (prev.distance < curr.distance) ? prev : curr);
                            console.log("Tujuan terdekat:", nearestGoal.name);
                            drawRouteToGoal(nearestGoal.location);
                        }
                    } else {
                        console.error('Directions request failed due to ' + status);
                    }
                });
            });
        }

        function drawRouteToGoal(goalLocation) {
            const directionsService = new google.maps.DirectionsService();
            const directionsRenderer = new google.maps.DirectionsRenderer();
            directionsRenderer.setMap(map);

            const request = {
                origin: start,
                destination: goalLocation,
                travelMode: google.maps.TravelMode.DRIVING,
            };

            directionsService.route(request, (result, status) => {
                if (status === google.maps.DirectionsStatus.OK) {
                    directionsRenderer.setDirections(result);
                } else {
                    console.error('Directions request failed due to ' + status);
                }
            });
        }

        function onButtonClick() {
            findNearestGoal();
        }

        // Inis ialisasi peta saat halaman dimuat
        window.onload = initMap;

        // Fungsi untuk mengisi data tujuan dari PHP
        function setGoals(data) {
            data.forEach(item => {
            goals.push({
            name: item.nama_Barang, // Ganti dengan nama yang sesuai
            location: { lat: parseFloat(item.latitude), lng: parseFloat(item.longitude) },
            description: `Barang: ${item.nama_Barang}<br>Penerima: ${item.nama_Penerima}<br>No Telepon: ${item.no_Telepon}` // Deskripsi tujuan
            });
        });
    }   
// Menandai semua tujuan dan menambahkan deskripsi
        goals.forEach(goal => {
            const marker = new google.maps.Marker({
            position: goal.location,
            map: map,
            title: goal.name
        });

    const infoWindow = new google.maps.InfoWindow({
        content: `<h4>${goal.name}</h4><p>${goal.description}</p>` // Menggunakan deskripsi yang telah diperbarui
    });

    marker.addListener('click', () => {
        infoWindow.open(map, marker);
    });
});
    </script>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>SPX EXPEDITION</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="all,follow">
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome CSS-->
    <link rel="stylesheet" href="vendor/font-awesome/css/font-awesome.min.css">
    <!-- Fontastic Custom icon font-->
    <link rel="stylesheet" href="css/fontastic.css">
    <!-- Google fonts - Poppins -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,700">
    <!-- theme stylesheet-->
    <link rel="stylesheet" href="css/style.default.css" id="theme-stylesheet">
    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="css/custom.css">
    <!-- Favicon-->
    <link rel="shortcut icon" href="../images/logo_upr.png">
</head>
<body>
<?php 
      include 'header.php';
      include 'siderbar.php';
      ?>
    <div id="map" style="height: 500px; width: 100%;"></div>
    <button  class="btn btn-primary btn-lg" onclick="onButtonClick()">Cari Rute Terpendek</button>
    <?php
    // Query untuk mengambil paket dengan filter yang ditentukan
    $sql = "SELECT d.no_delivery, d.resi, d.id_Kurir, k.nama_Kurir, p.nama_Penerima, p.alamat_Penerima, p.no_Telepon, p.nama_Barang, p.Quantity, p.latitude, p.longitude, p.status_paket 
        FROM delivery d 
        JOIN paket p ON d.resi = p.resi 
        JOIN kurir k ON d.id_Kurir = k.id_Kurir 
        WHERE k.username = '$sesi' AND p.status_paket = 'Paket Menuju Alamat Pengantaran'";

    $result = mysqli_query($koneksi, $sql);

    // Mengambil data dan mengisi array goals
    if ($result && mysqli_num_rows($result) > 0) {
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        echo '<script>setGoals(' . json_encode($data) . ');</script>';
    } else {
        echo '<script>console.log("Tidak ada paket yang ditemukan.");</script>';
    }

    // Tutup koneksi
    mysqli_close($koneksi);
    ?>
</body>
</html>