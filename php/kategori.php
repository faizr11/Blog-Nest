<?php
require __DIR__ . '../koneksi.php';

$sql = "SELECT * FROM category";
$result = mysqli_query($koneksi, $sql);

$categories = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($categories);
