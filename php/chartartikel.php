<?php
include 'koneksi.php';
$sql = "
SELECT 
    a.tanggal_publikasi,
    c.nama AS kategori,
    COUNT(*) AS jumlah
FROM articles a
JOIN article_category ac ON a.id = ac.artikel_id
JOIN category c ON ac.kategori_id = c.id
WHERE a.tanggal_publikasi IS NOT NULL
GROUP BY a.tanggal_publikasi, c.nama
ORDER BY a.tanggal_publikasi ASC
";

$result = $koneksi->query($sql);

$data = [];
$warnaKategori = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tanggal = $row['tanggal_publikasi'];
        $kategori = $row['kategori'];
        $jumlah = $row['jumlah'];

        $data[$tanggal][$kategori] = $jumlah;

        if (!isset($warnaKategori[$kategori])) {
            $warnaKategori[$kategori] = sprintf("#%06X", mt_rand(0, 0xFFFFFF));
        }
    }
}

echo json_encode([
    "data" => $data,
    "warna" => $warnaKategori
]);
