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
$id = $_POST['id'] ?? null;
$judul = $_POST['judul'] ?? null;
$isi = $_POST['isi'] ?? null;
$kutipan = $_POST['kutipan'] ?? null;
$penulis_id = $_SESSION['id'];
$kategori_id_list = isset($_POST['kategori_id']) ? json_decode($_POST['kategori_id'], true) : null;

// Validasi data wajib
if (!$id || !$judul || !$isi || !$kutipan) {
    echo json_encode(['success' => false, 'message' => 'Semua field wajib diisi']);
    exit;
}

// Upload gambar jika ada
$gambar_path = null;
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

// Koneksi ke DB
require __DIR__ . '/../koneksi.php';

// Bangun query update
$updateQuery = "UPDATE articles SET judul = ?, isi = ?, kutipan = ?";
$params = [$judul, $isi, $kutipan];
$types = "sss";

if ($gambar_path !== null) {
    $updateQuery .= ", gambar = ?";
    $params[] = $gambar_path;
    $types .= "s";
}

$updateQuery .= " WHERE id = ? AND penulis_id = ?";
$params[] = $id;
$params[] = $penulis_id;
$types .= "ii";

// Eksekusi update
$stmt = $koneksi->prepare($updateQuery);
$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    // Update kategori jika dikirim
    if (!empty($kategori_id_list) && is_array($kategori_id_list)) {
        // Hapus kategori lama
        $deleteStmt = $koneksi->prepare("DELETE FROM article_category WHERE artikel_id = ?");
        $deleteStmt->bind_param("i", $id);
        $deleteStmt->execute();
    
        // Masukkan kategori baru jika ada
        $insertStmt = $koneksi->prepare("INSERT INTO article_category (artikel_id, kategori_id) VALUES (?, ?)");
        foreach ($kategori_id_list as $kategori_id) {
            $insertStmt->bind_param("ii", $id, $kategori_id);
            $insertStmt->execute();
        }
    }
    
    

    echo json_encode(['success' => true, 'message' => 'Artikel berhasil diperbarui']);
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal memperbarui artikel', 'error' => $stmt->error]);
}
?>
