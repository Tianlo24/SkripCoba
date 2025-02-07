<?php 
include '../koneksi.php'; 
if(isset($_POST['input'])){

    // ambil data dari formulir
    $no_delivery = $_POST['no_delivery'];
    $resi = $_POST['resi'];
    $id_Kurir = $_POST['id_Kurir'];
    $sql = "INSERT INTO paket delivery('$no_delivery','$resi','$id_Kurir')";
     $query = mysqli_query($koneksi, $sql);
     header("location:delivery.php");
}
?>