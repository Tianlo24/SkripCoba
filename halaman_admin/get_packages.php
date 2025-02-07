<?php
$servername = "localhost"; // Ganti dengan nama server Anda
$username = "root"; // Ganti dengan username database Anda
$password = ""; // Ganti dengan password database Anda
$dbname = "spx"; // Ganti dengan nama database Anda

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query untuk mengambil data paket
$sql = "SELECT no, nama_Barang, latitude, longitude, nama_Penerima, no_Telepon FROM paket";
$result = $conn->query($sql);

$packages = array();

if ($result->num_rows > 0) {
    // Mengambil data setiap baris
    while($row = $result->fetch_assoc()) {
        $packages[] = $row;
    }
}

// Mengembalikan data dalam format JSON
header('Content-Type: application/json');
echo json_encode($packages);

$conn->close();
?>