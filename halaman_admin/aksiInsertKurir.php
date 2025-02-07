
<?php
include '../koneksi.php';


    $id_Kurir = $_POST['id_Kurir'];
    $nama_Kurir = $_POST['nama_Kurir'];
    $username = $_POST['username'];
    $pass = $_POST['password'];
    $telepon = $_POST['no_Telepon_Kurir'];

// Menyimpan data ke database
$sql = "INSERT INTO kurir (id_Kurir, nama_Kurir, username, password, no_Telepon_Kurir) VALUES (?, ?, ?, ?, ?)";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("issss", $id_Kurir, $nama_Kurir, $username, $pass, $telepon);

if ($stmt->execute()) {
    echo "<script>alert('Data berhasil disimpan'); window.location='kurir.php';</script>";
} else {
    echo "Error: " . $stmt->error;
}

// Tutup koneksi
$stmt->close();
$koneksi->close();
?>