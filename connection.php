<?php
$host = "127.0.0.1";
$user = "root";
$pass = "root";
$db   = "db_perpustakaan";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi Gagal: " . mysqli_connect_errno() . " - " . mysqli_connect_error());
}

echo "";
?>