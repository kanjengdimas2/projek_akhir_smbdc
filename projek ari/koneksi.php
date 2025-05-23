<?php
    session_start();
    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "toko_baju";

    $conn = mysqli_connect($host, $user, $password, $database);

    if (!$conn) {
        die("Koneksi gagal: " . mysqli_connect_error());
    }
?>