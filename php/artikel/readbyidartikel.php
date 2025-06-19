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
");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if (!$result || $result->num_rows === 0) {
    die("Artikel tidak ditemukan.");
}

$article = null;
$kategori = [];

while ($row = $result->fetch_assoc()) {
    if (!$article) {
        $article = [
            "id" => $row["id"],
            "judul" => $row["judul"],
            "isi" => $row["isi"],
            "gambar" => $row["gambar"] ? "/TheBlogNest/" . $row["gambar"] : null,
            "creator_nama" => $row["creator_nama"],
            "created_at" => $row["created_at"],
            "tanggal_publikasi" => $row["tanggal_publikasi"],
            "kutipan" => $row["kutipan"],
            "kategori" => []
        ];
    }

    // ✅ Tambahkan kategori ke array kategori jika belum ada
    if ($row["kategori_nama"] && !in_array($row["kategori_nama"], $kategori)) {
        $kategori[] = $row["kategori_nama"];
    }
}

// ✅ Ubah array kategori jadi string
$article["kategori"] = implode(", ", $kategori);

return $article;
