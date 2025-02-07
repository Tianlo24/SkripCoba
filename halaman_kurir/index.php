<!DOCTYPE html>
<?php
session_start(); // Pastikan session dimulai
include '../koneksi.php';
include '../fungsi.php';

$sesi = $_SESSION['username'];
// Pastikan session username sudah diset
if (!isset($_SESSION['username'])) {
    die("Anda harus login terlebih dahulu.");
}

// Ambil username dari session

// Ambil status_paket dari parameter GET (jika ada)
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'Paket Menuju Alamat Pengantaran'; // Default ke status ini

// Query untuk mengambil paket berdasarkan username dari tabel kurir dan status_paket
$sql = 'SELECT d.no_delivery, d.resi, d.id_Kurir, k.nama_Kurir, p.nama_Penerima, p.alamat_Penerima, p.no_Telepon, p.nama_Barang, p.Quantity, p.latitude, p.longitude, p.status_paket 
        FROM delivery d 
        JOIN paket p ON d.resi = p.resi 
        JOIN kurir k ON d.id_Kurir = k.id_Kurir 
        WHERE k.username = ? AND p.status_paket = ?';

// Persiapkan statement
$stmt = $koneksi->prepare($sql);

// Bind parameter
$stmt->bind_param("ss", $sesi, $status_filter); // Bind username dan status_paket

// Eksekusi statement
$stmt->execute();
$result = $stmt->get_result();

// Fungsi untuk mengubah status paket
if (isset($_GET['action']) && isset($_GET['resi'])) {
    $resi = $_GET['resi'];
    $last_updated_by = $_SESSION['username']; // Ambil username dari session

    if ($_GET['action'] == 'kirim') {
        // Update status paket
        $update_sql = "UPDATE paket SET status_paket = 'Paket Diterima Oleh Pembeli', last_updated_by = ? WHERE resi = ?";
        $update_stmt = $koneksi->prepare($update_sql);
        $update_stmt->bind_param("ss", $last_updated_by, $resi);
        $update_stmt->execute();
        $update_stmt->close();

        // Hapus data dari tabel delivery
        $delete_sql = "DELETE FROM delivery WHERE resi = ?";
        $delete_stmt = $koneksi->prepare($delete_sql);
        $delete_stmt->bind_param("s", $resi);
        $delete_stmt->execute();
        $delete_stmt->close();

        header("Location: " . $_SERVER['PHP_SELF']); // Redirect ke halaman yang sama
        exit();
    } elseif ($_GET['action'] == 'onhold') {
        $update_sql = "UPDATE paket SET status_paket = 'Pembeli Tidak Ada Ditempat', last_updated_by = ? WHERE resi = ?";
        $update_stmt = $koneksi->prepare($update_sql);
        $update_stmt->bind_param("ss", $last_updated_by, $resi);
        $update_stmt->execute();
        $update_stmt->close();
        
        header("Location: " . $_SERVER['PHP_SELF']); // Redirect ke halaman yang sama
        exit();
    }
}
?>

<html>
<head>
    <title>EXPRESS EXPEDITION</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>EXPRESS EXPEDITION</title>
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
                    <h2 class="no-margin-bottom">Tugas</h2>
                </div>
            </header>
            <br><br>
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <b>Daftar Tugas </b>
                        <table class="table"> 
                            <br><br>
                            <thead>
                                <tr>
                                    <th scope="col">No Delivery</th>
                                    <th scope="col">Resi</th>
                                    <th scope="col">Nama Kurir</th>
                                    <th scope="col">Nama Penerima</th>
                                    <th scope="col">Alamat Penerima</th>
                                    <th scope="col">No Telepon Pembeli</th>
                                    <th scope="col">Nama Barang</th>
                                    <th scope="col">Status Paket</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['no_delivery']; ?></td>
                                    <td><?php echo $row['resi']; ?></td>
                                    <td><?php echo $row['nama_Kurir']; ?></td>
                                    <td><?php echo $row['nama_Penerima']; ?></td>
                                    <td><?php echo $row['alamat_Penerima']; ?></td>
                                    <td><?php echo $row['no_Telepon']; ?></td>
                                    <td><?php echo $row['nama_Barang']; ?></td>
                                    <td><?php echo $row['status_paket']; ?></td>
                                    <td>
                                        <a href="?action=kirim&resi=<?php echo $row['resi']; ?>" class="btn btn-success">Kirim</a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- JavaScript files-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="js/front.js"></script>
</body>
</html>