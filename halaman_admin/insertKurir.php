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
}

// Fungsi untuk menghasilkan ID kurir yang unik
function generateUniqueKurirId($koneksi) {
    do {
        // Menghasilkan ID kurir acak 6 digit
        $id_kurir = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT); // Menghasilkan 6 digit dengan padding 0

        // Cek apakah ID kurir sudah ada di database
        $sql = "SELECT COUNT(*) FROM kurir WHERE id_Kurir = ?";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("s", $id_kurir);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
    } while ($count > 0); // Ulangi jika ID kurir sudah ada

    return $id_kurir; // Kembalikan ID kurir yang unik
}

// Menghasilkan ID kurir yang unik
$id_kurir = generateUniqueKurirId($koneksi);
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
                    <h2 class="no-margin-bottom">Input Kurir</h2>
                </div>
            </header>
            <br><br>
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <form action="aksiInsertKurir.php" method="POST">
                            <div class="form-group">
                                <label for="idKurir">ID Kurir</label>
                                <input class="form-control" type="text" name="id_Kurir" value="<?php echo $id_kurir; ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="namaKurir">Nama Kurir</label>
                                <input class="form-control" type="text" name="nama_Kurir" placeholder="Masukkan Nama Kurir" required>
                            </div>
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input class="form-control" type="text" name="username" placeholder="Masukkan Username" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input class="form-control" type="password" name="password" placeholder="Masukkan Password" required>
                            </div>
                            <div class="form-group">
                                <label for="noTelepon">No Telepon</label>
                                <input class="form-control" type="text" name ="no_Telepon_Kurir" placeholder="Masukkan No Telepon" required>
                            </div>
                            <button type="submit" name="input" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Page Footer-->
            <?php 
            include 'footer.php';
            ?>
        </div>
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