<?php 
// mengaktifkan session pada php
session_start();
error_reporting();

// menghubungkan php dengan koneksi database
include 'koneksi.php';
include 'fungsi.php';

// menangkap data yang dikirim dari form login
$username = mysql_real_escape_string($_POST['username']);
$password = mysql_real_escape_string($_POST['pass']);

// menyeleksi data user dengan username dan password yang sesuai
$login = mysqli_query($koneksi,"select * from tbl_user where binary username='$username' and binary password='$password'");

// menghitung jumlah data yang ditemukan
$cek = mysqli_num_rows($login);

// cek apakah username dan password di temukan pada database
if($cek > 0){

	$data = mysqli_fetch_assoc($login);

	// cek jika user login sebagai admin
	if($data['level']=="Palangka Raya"){

		// buat session login dan username
		$_SESSION['username'] = $username;
		$_SESSION['level'] = "Palangka Raya";
		// alihkan ke halaman dashboard admin
		header("location: halaman_admin/index.php");  
	    


	// cek jika user login sebagai user
	}else if($data['level']=="Banjar"){
		// buat session login dan username
		$_SESSION['username'] = $username;
		$_SESSION['level'] = "Banjar";

		// alihkan ke halaman dashboard user
		header("location: halaman_admin/index.php");
	}
}else{

		// alihkan ke halaman login kembali
		header("location:index.php?pesan=gagal");
	}
?>