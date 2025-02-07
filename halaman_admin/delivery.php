<?php
session_start();
include '../koneksi.php';
include '../fungsi.php';
error_reporting();

if(!isset($_SESSION['username'])){
    echo "<script>alert('Anda Harus Login Terlebih Dahulu'); window.location='../index.php'; </script>";
}

if ($_SESSION['level'] != 'Palangka Raya' && $_SESSION['level'] != 'Banjar')  {
     echo "<script>alert('Anda Harus Login Terlebih Dahulu'); window.location='../index.php'; </script>";
} else { 
$sesi = $_SESSION['username'];
}


// Ambil data latitude, longitude, dan resi dari tabel paket
$query = "SELECT latitude, longitude, resi FROM paket WHERE status_paket = 'Paket Diterima di Palangka Raya'";
$result = $koneksi->query($query);

$coordinates = [];
$names = []; // Array untuk menyimpan resi
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $coordinates[] = [(float)$row['latitude'], (float)$row['longitude']];
        $names[] = $row['resi']; // Simpan resi
    }
} 

// Ambil id_Kurir dari tabel kurir
$queryKurir = "SELECT id_Kurir FROM kurir";
$resultKurir = $koneksi->query($queryKurir);

$idKurirs = []; // Array untuk menyimpan id_Kurir
if ($resultKurir->num_rows > 0) {
    while ($rowKurir = $resultKurir->fetch_assoc()) {
        $idKurirs[] = $rowKurir['id_Kurir']; // Simpan id_Kurir
    }
} else {
    echo "0 kurir results";
}

$koneksi->close();

// Fungsi K-Means
function kMeans($data, $k) {
    // Inisialisasi centroid secara acak
    $centroids = array_slice($data, 0, $k);
    $clusters = array_fill(0, count($data), -1);
    $changed = true;

    while ($changed) {
        $changed = false;

        // Menetapkan setiap titik ke centroid terdekat
        foreach ($data as $i => $point) {
            $closest = findClosestCentroid($point, $centroids);
            if ($clusters[$i] !== $closest) {
                $clusters[$i] = $closest;
                $changed = true;
            }
        }

        // Menghitung centroid baru
        $centroids = updateCentroids($data, $clusters, $k);
    }

    return $clusters;
}

function findClosestCentroid($point, $centroids) {
    $closest = 0;
    $minDistance = PHP_INT_MAX;

    foreach ($centroids as $i => $centroid) {
        $distance = sqrt(pow($point[0] - $centroid[0], 2) + pow($point[1] - $centroid[1], 2));
        if ($distance < $minDistance) {
            $minDistance = $distance;
            $closest = $i;
        }
    }

    return $closest;
}

function updateCentroids($data, $clusters, $k) {
    $newCentroids = array_fill(0, $k, [0, 0]);
    $counts = array_fill(0, $k, 0);

    foreach ($data as $i => $point) {
        $cluster = $clusters[$i];
        $newCentroids[$cluster][0] += $point[0];
        $newCentroids[$cluster][1] += $point[1];
        $counts[$cluster]++;
    }

    foreach ($newCentroids as $i => $centroid) {
        if ($counts[$i] > 0) {
            $newCentroids[$i][0] /= $counts[$i];
            $newCentroids[$i][1] /= $counts[$i];
        }
    }

    return $newCentroids;
}

// Lakukan klustering
$k = max(1, count($idKurirs)); // Jumlah cluster berdasarkan jumlah kurir, minimal 1
$clusters = kMeans($coordinates, $k);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Express Expedition</title>
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
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.default.css" id="theme-stylesheet">
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA7ROdAUadsuUA9nXde60Xm1qzAx8y46EM"></script>
    <script>
        function initMap() {
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 13,
                center: { lat: -2.2160485107596006, lng: 113.91333671567924 },
            });

            const coordinates = <?php echo json_encode($coordinates); ?>;
            const clusters = <?php echo json_encode($clusters); ?>;
            const names = <?php echo json_encode($names); ?>;
            const idKurirs = <?php echo json_encode($idKurirs); ?>;

            clusters.forEach((cluster, index) => {
                const color = getColor(cluster);
                const marker = new google.maps.Marker({
                    position: { lat: coordinates[index][0], lng: coordinates[index][1] },
                    map: map,
                    title: names[index],
                    icon: {
                        path: google.maps.SymbolPath.CIRCLE,
                        scale: 10,
                        fillColor: color,
                        fillOpacity: 0.6,
                        strokeWeight: 1,
                        strokeColor: 'white'
                    }
                });

                const infoWindow = new google.maps.InfoWindow({
                    content: `<div>
                                <h3>${names[index]}</h3>
                                <p>ID Kurir: ${idKurirs[cluster]}</p>
                                <button onclick="addDelivery('${idKurirs[cluster]}', '${names[index]}')">Add Delivery</button>
                              </div>`
                });

                marker.addListener('click', () => {
                    infoWindow.open(map, marker);
                });
            });

            // Tambahkan tombol untuk menambahkan semua delivery
            const addAllButton = document.createElement('button');
            addAllButton.innerText = 'TUGASKAN SEMUA PAKET';
            addAllButton.style.position = 'absolute';
            addAllButton.style.top = '525px';
            addAllButton.style.left = '700px';
            addAllButton.onclick = () => addAllDeliveries(coordinates, clusters, names, idKurirs);
            document.body.appendChild(addAllButton);
        }

        function addAllDeliveries(coordinates, clusters, names, idKurirs) {
            console.log("Add All Deliveries clicked");
            const deliveries = coordinates.map((coord, index) => {
            const noDelivery = Math.floor(1000 + Math.random() * 9000);
            return {
                idKurir: idKurirs[clusters[index]],
                resi: names[index],
                noDelivery: noDelivery
            };
        });

    fetch('add_delivery.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(deliveries),
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log("Deliveries to be sent:", deliveries);
        // Tampilkan pesan dari server
        alert(data.message || "Pengiriman berhasil diproses.");

        // Jika semua pengiriman berhasil, update status paket
        if (data.message === 'Semua Paket Berhasil Ditugaskan') {
            const resis = deliveries.map(delivery => delivery.resi);
            return fetch('update_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(resis),
            });
        }
    })
    .then(response => {
        if (response) {
            return response.json();
        }
    })
    .then(data => {
        if (data) {
            alert(data.message);
        }
    })
    .catch((error) => {
        console.error('Error:', error);
    });
}

function updatePackageStatus(deliveries) {
    // Ambil resi dari setiap pengiriman
    const resis = deliveries.map(delivery => delivery.resi);

    // Kirim permintaan untuk memperbarui status paket
    fetch('update_status.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(resis),
    })
    .then(response => {
        // Periksa apakah respons berhasil
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json(); // Mengembalikan data JSON
    })
    .then(data => {
        // Tampilkan pesan dari server
        if (data.message) {
            alert(data.message); // Tampilkan pesan sukses
        } else {
            // Jika tidak ada pesan, tampilkan pesan default
            alert("Status paket berhasil diperbarui.");
        }
    })
    .catch((error) => {
        // Tangani kesalahan
        console.error('Error:', error);
        alert("Terjadi kesalahan saat memperbarui status paket. Silakan coba lagi.");
    });
}

        function getColor(cluster) {
            const colors = ['#FF0000', '#00FF00', '#0000FF', '#FFFF00', '#FF00FF', '#00FFFF'];
            return colors[cluster % colors.length]; // Menggunakan warna berdasarkan cluster
        }
    </script>
</head>
<body onload="initMap()">
    <?php 
        include 'header.php';
        include 'siderbar.php';
        ?>
    <br><br>
    <div id="map" style="height: 500px; width: 100%;"></div>
    <?php 
        include 'footer.php';
    ?>
</body>
</html>