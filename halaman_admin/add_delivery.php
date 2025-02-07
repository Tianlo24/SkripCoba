<?php
session_start(); // Memulai session

// Koneksi ke database
$servername = "localhost"; // Ganti dengan server Anda
$username = "root"; // Ganti dengan username Anda
$password = ""; // Ganti dengan password Anda
$dbname = "coba"; // Ganti dengan nama database Anda

$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil data dari permintaan
$data = json_decode(file_get_contents('php://input'), true);

// Array untuk menyimpan hasil
$results = [];
$allSuccess = true; // Flag untuk mengecek apakah semua pengiriman berhasil

foreach ($data as $delivery) {
    $resi = $delivery['resi'];

    // Cek apakah resi sudah ada di tabel delivery
    $checkQuery = "SELECT COUNT(*) as count FROM delivery WHERE resi = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("s", $resi);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['count'] > 0) {
        // Jika resi sudah ada, tambahkan pesan ke hasil
        $results[] = [
            'resi' => $resi,
            'status' => 'error',
            'message' => 'Resi ' . $resi . ' sudah ditugaskan.'
        ];
        $allSuccess = false; // Set flag ke false jika ada error
    } else {
        // Jika resi belum ada, lakukan insert
        $idKurir = $delivery['idKurir'];
        $noDelivery = $delivery['noDelivery'];

        $insertQuery = "INSERT INTO delivery (resi, id_Kurir, no_delivery) VALUES (?, ?, ?)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param("iii", $resi, $idKurir, $noDelivery);
        $insertStmt->execute();

       
    }

    $stmt->close();
}

// Tutup koneksi
$conn->close();

// Kembalikan hasil sebagai JSON
if ($allSuccess) {
    echo json_encode(['message' => 'Semua Paket Berhasil Ditugaskan']);
} else {
    echo json_encode($results);
}
?>