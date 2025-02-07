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
    <!-- Tweaks for older IEs--><!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
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
      </br>
      </br>
          <div class="container-fluid">
                  <div class="card">
                    <div class="card-body" style="">
                      <p style="text-align: center;">Selamat Datang <strong><?php echo $dt['nama'] ?></strong>, Anda Login Sebagai Operator!</p>
                    </div>
                  </div>
                </div>
          <!-- Dashboard Counts Section-->
          <section class="dashboard-counts no-padding-bottom">
            <div class="container-fluid">
              <div class="row bg-white has-shadow">
                <!-- Item -->
                <div class="col-xl-4 col-sm-6">
                  <div class="item d-flex align-items-center">
                    <div class="icon bg-violet"><i class="icon-user"></i></div>
                    <div class="title"><span>Jumlah</br>Kurir</span>
                    </div>
                    <?php 
                    $dt = query("select * from kurir");
                    $d = mysql_num_rows($dt);
                     ?>
                    <div class="number"><strong><?php echo $d ?></strong></div>
                  </div>
                </div>
                <!-- Item -->
                <div class="col-xl-4 col-sm-6">
                  <div class="item d-flex align-items-center">
                    <div class="icon bg-red"><i class="icon-user"></i></div>
                    <div class="title"><span>Jumlah</br>Operator</span>
                    </div>
                    <?php 
                        // Misalkan $conn adalah koneksi ke database Anda
                        $stmt = $koneksi->prepare("SELECT * FROM tbl_user WHERE username = ?");
                        $stmt->bind_param("s", $sesi);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $dta = $result->fetch_all(MYSQLI_ASSOC);
                        $jumlah_baris = $result->num_rows;

                     ?>
                    <div class="number"><strong><?php echo $jumlah_baris ?></strong></div>
                  </div>
                </div>
                <!-- Item -->
                <div class="col-xl-4 col-sm-6">
                  <div class="item d-flex align-items-center">
                    <div class="icon bg-green"><i class="icon-home"></i></div>
                    <div class="title"><span>Jumlah</br>Paket</span>
                    </div>
                     <?php 
                    $dt = query("select * from paket");
                    $d = mysql_num_rows($dt);
                     ?>
                    <div class="number"><strong><?php echo $d ?></strong></div>
                  </div>
                </div>
              </div>
            </div>
          </section>

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