<?php
require __DIR__.'/../koneksi.php';

$sql = "SELECT a.id, a.judul, a.isi, a.penulis_id, a.gambar, a.tanggal_publikasi, a.kutipan, c.nama AS kategori
        FROM articles a
        JOIN article_category ac ON a.id = ac.artikel_id
        JOIN category c ON ac.kategori_id = c.id
        ORDER BY a.created_at DESC";


$result = mysqli_query($koneksi,$sql);

if (!$result) {
    http_response_code(500);
    return []; 
}

$articles = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id = (int)$row["id"];

        // Jika artikel belum ada di array, buat entry baru
        if (!isset($articles[$id])) {
            $articles[$id] = [
                "id" => $id,
                "judul" => $row["judul"],
                "isi" => $row["isi"],
                "kutipan" => $row["kutipan"],
                "penulis_id" => $row["penulis_id"] !== null ? (int)$row["penulis_id"] : null,
                "kategori" => [], // array kategori
                "gambar" => $row["gambar"] ? "/TheBlogNest/" . $row["gambar"] : null,
                "tanggal_publikasi" => $row["tanggal_publikasi"]
            ];
        }

        // Tambahkan kategori ke array kategori jika belum ada
        if (!in_array($row["kategori"], $articles[$id]["kategori"])) {
            $articles[$id]["kategori"][] = $row["kategori"];
        }
    }

    // Jika ingin ubah array kategori jadi string, gunakan ini:
    $articles = array_values(array_map(function($article) {
        $article["kategori"] = implode(", ", $article["kategori"]);
        return $article;
    }, $articles));
}


return $articles;
