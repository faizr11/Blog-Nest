<?php
session_start();
require __DIR__ . '/../koneksi.php';

$userId = $_SESSION['id']; 

$sql = "SELECT a.id, a.judul, a.isi, a.penulis_id, a.gambar, a.kutipan,
               DATE(a.created_at) AS tanggal_publikasi,
               c.nama AS kategori,
               u.nama AS penulis,
               u.foto_profil AS foto,
               u.created_at
        FROM articles a
        JOIN article_category ac ON a.id = ac.artikel_id
        JOIN category c ON ac.kategori_id = c.id
        JOIN users u ON a.penulis_id = u.id
        WHERE a.penulis_id = ?
        ORDER BY a.created_at DESC";

$stmt = $koneksi->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    http_response_code(500);
    return [];
}

$articles = [];
$profile = null;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id = (int) $row["id"];

        if (!isset($articles[$id])) {
            $articles[$id] = [
                "id" => $id,
                "judul" => $row["judul"],
                "isi" => $row["isi"],
                "penulis_id" => $row["penulis_id"] !== null ? (int) $row["penulis_id"] : null,
                "kategori" => [],
                "gambar" => $row["gambar"] ? "/TheBlogNest/" . $row["gambar"] : null,
                "tanggal_publikasi" => $row["tanggal_publikasi"],
                "kutipan" => $row["kutipan"]
            ];

            if (!$profile) {
                $createdAtFormatted = $row["created_at"] ? date("d F Y", strtotime($row["created_at"])) : null;
                $profile = [
                    "penulis" => $row["penulis"],
                    "foto" => $row["foto"] ? "/TheBlogNest/" . $row["foto"] : null,
                    "tanggal_bergabung" => $createdAtFormatted
                ];
            }
        }

        if (!in_array($row["kategori"], $articles[$id]["kategori"])) {
            $articles[$id]["kategori"][] = $row["kategori"];
        }
    }

    $articles = array_values(array_map(function ($article) {
        $article["kategori"] = implode(", ", $article["kategori"]);
        return $article;
    }, $articles));
} else {
    $sqlUser = "SELECT nama AS penulis, foto_profil AS foto, created_at FROM users WHERE id = ?";
    $stmtUser = $koneksi->prepare($sqlUser);
    $stmtUser->bind_param("i", $userId);
    $stmtUser->execute();
    $userResult = $stmtUser->get_result();

    if ($userRow = $userResult->fetch_assoc()) {
        $createdAtFormatted = $userRow["created_at"] ? date("d F Y", strtotime($userRow["created_at"])) : null;
        $profile = [
            "penulis" => $userRow["penulis"],
            "foto" => $userRow["foto"] ? "/TheBlogNest/" . $userRow["foto"] : null,
            "tanggal_bergabung" => $createdAtFormatted
        ];
    }
}

$sqlCount = "SELECT COUNT(*) AS total FROM articles WHERE penulis_id = ?";
$stmtCount = $koneksi->prepare($sqlCount);
$stmtCount->bind_param("i", $userId);
$stmtCount->execute();
$countResult = $stmtCount->get_result();
$totalArtikel = 0;

if ($row = $countResult->fetch_assoc()) {
    $totalArtikel = (int) $row['total'];
}

if ($profile) {
    $profile["total_artikel"] = $totalArtikel;
}

return [
    "profile" => $profile,
    "articles" => $articles
];
