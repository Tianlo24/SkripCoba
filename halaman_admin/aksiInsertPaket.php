<?php
include '../koneksi.php';


// Mengambil data dari form
$nama_Penerima = $_POST['nama_Penerima'];
$alamat_Penerima = $_POST['alamat_Penerima'];
$no_Telepon = $_POST['no_Telepon'];
$nama_Barang = $_POST['nama_Barang'];
$quantity = (int)$_POST['Quantity']; // Pastikan ini adalah integer
$latitude = (float)$_POST['latitude']; // Pastikan ini adalah float
$longitude = (float)$_POST['longitude']; // Pastikan ini adalah float
$status = $_POST['status_paket'];
$curent_loc = $_POST['last_updated_by'];

// Fungsi untuk menghasilkan nomor resi yang unik
function generateUniqueResi($koneksi) {
    do {
        // Menghasilkan nomor resi
        $tanggal = date('d');
        $bulan = date('m');
        $tahun = date('y');
        $angkaAcak = '';
        for ($i = 0; $i < 3; $i++) {
            $angkaAcak .= mt_rand(0, 9); // Menghasilkan angka acak antara 0 dan 9
        }
        $resi = $tahun . $bulan . $tanggal . $angkaAcak; // Format: DDMMYYYYXXX

        // Cek apakah nomor resi sudah ada di database
        $sql = "SELECT COUNT(*) FROM paket WHERE resi = ?";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("i", $resi);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
    } while ($count > 0); // Ulangi jika nomor resi sudah ada

    return $resi; // Kembalikan nomor resi yang unik
}

// Menghasilkan nomor resi yang unik
$resi = generateUniqueResi($koneksi);

// Menyimpan data ke database
$sql = "INSERT INTO paket (resi, nama_Penerima, alamat_Penerima, no_Telepon, nama_Barang, Quantity, latitude, longitude, status_paket, last_updated_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("issssiddss", $resi, $nama_Penerima, $alamat_Penerima, $no_Telepon, $nama_Barang, $quantity, $latitude, $longitude, $status,  $curent_loc);

if ($stmt->execute()) {
    echo "<script>alert('Data berhasil disimpan dengan No Resi: $resi'); window.location='kirim.php';</script>";
} else {
    echo "Error: " . $stmt->error;
}

// Tutup koneksi
$stmt->close();
$koneksi->close();
?>