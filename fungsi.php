<?php

function konek() {
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db = "coba";

    $koneksi = mysql_connect($host, $user, $pass);
    if ($koneksi) {
        mysql_select_db($db, $koneksi);
    } else {
        echo mysql_error();
    }
}

function query($str) {
	konek();
    $ekse = mysql_query("$str");
    return $ekse;
}

?>