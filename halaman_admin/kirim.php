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

$sql = 'SELECT * FROM paket WHERE status_paket != "Paket Diterima Oleh Pembeli"';
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
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.default.css" id="theme-stylesheet">

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
              <h2 class="no-margin-bottom">Tambah Paket</h2>
            </div>
          </header>
      </br>
      </br>
      <div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <a href="insertPaket.php"><button type="button" class="btn btn-primary">Tambah Paket</button></a>
            <div class="table-responsive"> <!-- Tambahkan div ini untuk membuat tabel responsif -->
                <table class="table"> 
                    <br>
                    <br>
                    <thead>
                        <tr>
                            <th scope="col">No Resi</th>
                            <th scope="col">Nama Penerima</th>
                            <th scope="col">Alamat</th>
                            <th scope="col">No Telepon</th>
                            <th scope="col">Nama Barang</th>
                            <th scope="col">Quan</th>
                            <th scope="col">Latitude</th>
                            <th scope="col">Longitude</th>
                            <th scope="col">Status Paket</th>
                            <th scope="col">Update Terakhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = mysqli_fetch_array($query)) {
                            echo '<tr>
                                <td>'.$row['resi'].'</td>
                                <td>'.$row['nama_Penerima'].'</td>
                                <td>'.$row['alamat_Penerima'].'</td>
                                <td>'.$row['no_Telepon'].'</td>
                                <td>'.$row['nama_Barang'].'</td>
                                <td>'.$row['Quantity'].'</td>
                                <td>'.$row['latitude'].'</td>
                                <td>'.$row['longitude'].'</td>
                                <td>'.$row['status_paket'].'</td>
                                <td>'.$row['last_updated_by'].'</td>
                            </tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div> <!-- Tutup div responsif -->
        </div>
    </div>
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