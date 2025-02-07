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
$resis = $data; // Resi yang diterima

// Ambil username dari session
$sesi = $_SESSION['username'];

// Array untuk menyimpan hasil
$results = [];
$allUpdated = true; // Flag untuk mengecek apakah semua update berhasil

foreach ($resis as $resi) {
    // Update status paket dan last_updated_by
    $query = "UPDATE paket SET status_paket = 'Paket Menuju Alamat Pengantaran', last_updated_by = ? WHERE resi = ?";
    $stmt = $conn->prepare($query);
    
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ss", $sesi, $resi);
    
    if ($stmt->execute()) {
        // Jika update berhasil, tambahkan pesan sukses
        $results[] = [
            'resi' => $resi,
            'status' => 'success',
            'message' => 'Status paket untuk resi ' . $resi . ' berhasil diperbarui.'
        ];
    } else {
        // Jika update gagal, tambahkan pesan error
        $results[] = [
            'resi' => $resi,
            'status' => 'error',
            'message' => 'Gagal memperbarui status paket untuk resi ' . $resi . ': ' . $stmt->error
        ];
        $allUpdated = false; // Set flag ke false jika ada error
    }

    $stmt->close(); // Menutup statement setelah setiap eksekusi
}

// Tutup koneksi
$conn->close();

// Kembalikan hasil sebagai JSON
if ($allUpdated) {
    echo json_encode(['message' => 'Semua status paket berhasil diperbarui.']);
} else {
    echo json_encode($results);
}
?>