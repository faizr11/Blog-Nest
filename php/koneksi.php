<?php
// header("Access-Control-Allow-Origin: *");
// header("Content-Type: application/json; charset=UTF-8");
$host     = "localhost";     
$username = "root";          
$password = "";              
$database = "db_theblognest"; 

$koneksi = mysqli_connect($host, $username, $password, $database);

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// echo "Koneksi berhasil";
