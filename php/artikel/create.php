<?php
session_start();
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Cek request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Cek login
if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Anda harus login']);
    exit;
}

// Ambil data
$judul = $_POST['judul'] ;
$isi = $_POST['isi'] ;
$kutipan = $_POST['kutipan'] ;
$penulis_id = $_SESSION['id'];
$kategori_id_list = json_decode($_POST['kategori_id'] , true);

// Validasi
if (!$judul || !$isi || !$kutipan || empty($kategori_id_list)) {
    echo json_encode(['success' => false, 'message' => 'Semua field wajib diisi']);
    exit;
}

// Upload gambar
$gambar_path = '';
if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = __DIR__ . '/../../uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $filename = uniqid() . '-' . basename($_FILES['gambar']['name']);
    $targetFile = $uploadDir . $filename;

    if (move_uploaded_file($_FILES['gambar']['tmp_name'], $targetFile)) {
        $gambar_path = 'uploads/' . $filename;
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal upload gambar']);
        exit;
    }
}

// Koneksi DB
require __DIR__ . '/../koneksi.php';

// Simpan artikel
$tanggal_publikasi = date('Y-m-d');
$stmt = $koneksi->prepare("INSERT INTO articles (judul, isi, kutipan, penulis_id, gambar, tanggal_publikasi, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");

$stmt->bind_param("sssiss", $judul, $isi, $kutipan, $penulis_id, $gambar_path, $tanggal_publikasi);

if ($stmt->execute()) {
    $artikel_id = $stmt->insert_id;

    // Simpan kategori (pivot)
    $catStmt = $koneksi->prepare("INSERT INTO article_category (artikel_id, kategori_id) VALUES (?, ?)");
    foreach ($kategori_id_list as $kategori_id) {
        $catStmt->bind_param("ii", $artikel_id, $kategori_id);
        $catStmt->execute();
    }

    echo json_encode(['success' => true, 'message' => 'Artikel berhasil disimpan', 'article_id' => $artikel_id]);
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal menyimpan artikel', 'error' => $stmt->error]);
}

?>
