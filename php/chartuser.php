<?php
include 'koneksi.php';
$sql = "
SELECT 
    u.nama AS penulis,
    c.nama AS kategori,
    COUNT(*) AS jumlah
FROM articles a
JOIN users u ON a.penulis_id = u.id
JOIN article_category ac ON a.id = ac.artikel_id
JOIN category c ON ac.kategori_id = c.id
WHERE u.role = 'penulis' AND u.status = 'aktif'
GROUP BY u.nama, c.nama
ORDER BY u.nama, c.nama
";
$result = $koneksi->query($sql);

// Susun data
$data = [];
$maxJumlah = 0;
$warnaKategori = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $penulis = $row['penulis'];
        $kategori = $row['kategori'];
        $jumlah = $row['jumlah'];

        $data[$penulis][$kategori] = $jumlah;
        if ($jumlah > $maxJumlah)
            $maxJumlah = $jumlah;

        if (!isset($warnaKategori[$kategori])) {
            $warnaKategori[$kategori] = sprintf("#%06X", mt_rand(0, 0xFFFFFF));
        }
    }
    echo json_encode([
        "data" => $data,
        "warna" => $warnaKategori
    ]);
   
} else {
    echo "Tidak ada data ditemukan.";
    exit;
}