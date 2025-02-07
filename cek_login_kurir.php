<?php 
// Mengaktifkan session pada PHP
session_start();
error_reporting();

// Menghubungkan PHP dengan koneksi database
include 'koneksi.php';
include 'fungsi.php';

// Menangkap data yang dikirim dari form login
$username = mysqli_real_escape_string($koneksi, $_POST['username']);
$password = mysqli_real_escape_string($koneksi, $_POST['pass']);

// Menyeleksi data kurir dengan username dan password yang sesuai
$sql = mysqli_query($koneksi, "SELECT * FROM kurir WHERE binary username='$username' AND binary password='$password'");

// Menghitung jumlah data yang ditemukan
$cek = mysqli_num_rows($sql);

// Cek apakah username dan password ditemukan pada database
if ($cek > 0) {
    $data = mysqli_fetch_assoc($sql);

    // Buat session login dan username
    $_SESSION['username'] = $username;
    $_SESSION['id_kurir'] = $data['id_Kurir']; // Menyimpan id kurir ke session

    // Alihkan ke halaman dashboard kurir
    header("location: halaman_kurir/index.php");  

} else {
    // Alihkan ke halaman login kembali
    header("location:index.php?pesan=gagal");
}
?>