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


// Cek koneksi
if ($koneksi->connect_error) {
    die("Connection failed: " . $koneksi->connect_error);
}

// Proses jika ada permintaan POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $no_resi = $_POST['resi'];
    $current_user = $_SESSION['username'];
    $current_loc = $_SESSION['level'];

    // Update status paket langsung menjadi "Paket Diterima di [current location]"
    $new_status = "Paket Diterima di " . $current_loc;

    // Update status paket dan siapa yang mengupdate
    $sql = "UPDATE paket SET status_paket = ?, last_updated_by = ? WHERE resi = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("sss", $new_status, $current_user, $no_resi);

    if ($stmt->execute()) {
        $message = "Paket Diterima ";
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Query untuk mengambil data dari tabel paket
$sql_paket = "SELECT * FROM paket WHERE status_paket = 'Pesanan Dibuat' AND last_updated_by != '$sesi'";
$query_paket = mysqli_query($koneksi, $sql_paket);

// Tutup koneksi
$koneksi->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Express Expedition</title>
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
                    <h2 class="no-margin-bottom">Receive Paket</h2>
                </div>
            </header> 
            <div class="card">
                <div class="card-body">
                    <div class="container">
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="resi">Nomor Resi:</label>
                                <input type="text" class="form-control" id="resi" name="resi" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Terima Paket</button>
                        </form>

                        <?php
                        // Tampilkan pesan jika ada
                        if (isset($message)) {
                            echo "<div class='alert alert-info mt-3'>$message</div>";
                        }
                        ?>
                    </div> 
                </div> 
            </div>

            <!-- Tabel Data Paket -->
            <div class="card">
                <div class="card-body">
                    <h2 class="no-margin-bottom">Data Paket</h2>
                    <table class="table"> 
                        <thead>
                            <tr>
                                <th scope="col">No Resi</th>
                                <th scope="col">Nama Penerima</th>
                                <th scope="col">Alamat</th>
                                <th scope="col">No Telepon</th>
                                <th scope="col">Nama Barang</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Latitude</th>
                                <th scope="col">Longitude</th>
                                <th scope="col">Status Paket</th>
                                <th scope="col">Update Terakhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($row_paket = mysqli_fetch_array($query_paket)) {
                                echo '<tr>
                                    <td>'.$row_paket['resi'].'</td>
                                    <td>'.$row_paket['nama_Penerima'].'</td>
                                    <td>'.$row_paket['alamat_Penerima'].'</td>
                                    <td>'.$row_paket['no_Telepon'].'</td>
                                    <td>'.$row_paket['nama_Barang'].'</td>
                                    <td>'.$row_paket['Quantity'].'</td>
                                    <td>'.$row_paket['latitude'].'</td>
                                    <td>'.$row_paket['longitude'].'</td>
                                    <td>'.$row_paket['status_paket'].'</td>
                                    <td>'.$row_paket['last_updated_by'].'</td>
                                </tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Page Footer-->
            <?php 
            include 'footer.php';
            ?>
        </div>
    </div>

    <!-- JavaScript files-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="vendor/popper.js/umd/popper.min.js"> </script>
    <script src="vendor/jquery.cookie/jquery.cookie.js"> </script>
    <script src="vendor/chart.js/Chart.min.js"></script>
    <script src="vendor/jquery-validation/jquery.validate.min.js"></script>
    <script src="js/charts-home.js"></script>
    <!-- Main File-->
    <script src="js/front.js"></script>
</body>
</html>