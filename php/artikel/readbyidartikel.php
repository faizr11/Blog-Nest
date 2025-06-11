<?php
require __DIR__ . '../../koneksi.php';

if (!isset($_GET['id'])) {
    die("ID tidak ditemukan.");
}

$id = intval($_GET['id']);

$stmt = $koneksi->prepare("
    SELECT 
        a.*, 
        u.nama AS creator_nama, 
        k.nama AS kategori_nama
    FROM articles a
    LEFT JOIN users u ON u.id = a.penulis_id
    LEFT JOIN article_category ak ON ak.artikel_id = a.id
    LEFT JOIN category k ON k.id = ak.kategori_id
    WHERE a.id = ?
    LIMIT 1
");

$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$article = $result->fetch_assoc();

if (!$article) {
    die("Artikel tidak ditemukan.");
}

return $article;
