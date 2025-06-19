<?php
require __DIR__ . '/../koneksi.php';

$sql = "SELECT c.id, c.nama, COUNT(ac.kategori_id) AS jumlah_digunakan
        FROM category c
        LEFT JOIN article_category ac ON c.id = ac.kategori_id
        GROUP BY c.id, c.nama
        ORDER BY jumlah_digunakan DESC"; 

$result = mysqli_query($koneksi, $sql);

if (!$result) {
    http_response_code(500);
    return [];
}

$categories = [];

while ($row = $result->fetch_assoc()) {
    $categories[] = [
        "id" => (int)$row["id"],
        "nama" => $row["nama"],
        "count" => (int)$row["jumlah_digunakan"]
    ];
}

return $categories;
