<?php
$host = "localhost";
$user = "root"; // ganti kalau user MySQL kamu beda
$pass = "";
$db   = "dbpoinsantri";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
