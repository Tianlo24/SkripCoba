<!DOCTYPE html>
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
    $current_loc = $_SESSION['level'];
}

$sql = 'SELECT no, resi, nama_Penerima, alamat_Penerima, no_Telepon, nama_Barang, Quantity, latitude, longitude FROM paket';
$query = mysqli_query($koneksi, $sql);
?>

<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Express Expedition</title>
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
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA7ROdAUadsuUA9nXde60Xm1qzAx8y46EM&callback=initMap" async defer></script>
    <script src="../halaman_admin/js/script.js" defer></script> <!-- Memanggil file JavaScript terpisah -->
    <script>
        let map; // Deklarasi variabel peta

        function initMap() {
            // Inisialisasi peta
            map = new google.maps.Map(document.getElementById("map"), {
                center: { lat: -2.2110, lng: 113.9213 },
                zoom: 12
            });
        }

        function geocodeAddress() {
            const address = document.getElementById('addressInput').value; // Ambil alamat dari input
            const geocoder = new google.maps.Geocoder(); // Inisialisasi geocoder
            geocoder.geocode({ 'address': address }, (results, status) => {
                if (status === 'OK') {
                    const latitude = results[0].geometry.location.lat();
                    const longitude = results[0].geometry.location.lng();
                    
                    // Masukkan latitude dan longitude ke dalam input field
                    document.getElementById('latitudeInput').value = latitude;
                    document.getElementById('longitudeInput').value = longitude;
                } else {
                    alert('Geocode was not successful for the following reason: ' + status);
                }
            });
        }

        function showMap() {
            document.getElementById('map').style.display = 'block'; // Menampilkan peta
            initMap(); // Memanggil fungsi initMap untuk memastikan peta diinisialisasi
        }
    </script>
</head>
<body>
    <div class="page">
        <!-- Main Navbar-->
        <?php 
        include 'header.php';
        include 'siderbar.php';
        ?>
        <div class="content-inner">
            <!-- Page Header-->
            <header class="page-header">
                <div class="container-fluid">
                    <h2 class="no-margin-bottom">Dashboard</h2>
                </div>
            </header>
            <br><br>
            <div class="container-fluid">
                <form action="aksiInsertPaket.php" method="POST">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Nama Penerima</label>
                        <input class="form-control" type="text" name="nama_Penerima" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Alamat Penerima</label>
                        <input class="form-control" type="text" id="addressInput" name="alamat_Penerima" placeholder="">
                    </div>
                    <button type="button" onclick="geocodeAddress()">Klik Setelah Menulis Alamat</button>
                    <div class="form-group">
                        <label for="exampleInputEmail1">No Telepon</label>
                        <input class="form-control" type="text" name="no_Telepon" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Nama Barang</label>
                        <input class="form-control" type="text" name="nama_Barang" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Quantity</label>
                        <input class="form-control" type="text" name="Quantity" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Latitude:</label>
                        <input class="form-control" type="text" id="latitudeInput" name="latitude" readonly />
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Longitude:</label>
                        <input class="form-control" type="text" id="longitudeInput" name="longitude" readonly />
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="hidden" name="status_paket" value="Pesanan Dibuat">
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="hidden" name="last_updated_by" value="<?php echo htmlspecialchars($sesi); ?>">
                    </div>
                    <button class="btn btn-primary" type="submit" name="input">Submit</button>
                    <div id="map" style="display: none; height: 500px; width: 100%;"></div>
                </form>
            </div>
            <br>
            <!-- Page Footer-->
            <?php 
            include 'footer.php';
            ?>
        </div>
    </div>
    <!-- JavaScript files-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/popper.js/umd/popper.min.js"> </script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="vendor/jquery.cookie/jquery.cookie.js"> </script>
    <script src="vendor/chart.js/Chart.min.js"></script>
    <script src="vendor/jquery-validation/jquery.validate.min.js"></script>
    <script src="js/charts-home.js"></script>
    <!-- Main File-->
    <script src="js/front.js"></script>
</body>
</html>