<?php
header('Content-Type: application/json');
require __DIR__ . '/../koneksi.php';

if (!isset($_POST['id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID artikel tidak diberikan']);
    exit;
}

$id = intval($_POST['id']);

$stmtGet = $koneksi->prepare("SELECT gambar FROM articles WHERE id = ?");
$stmtGet->bind_param("i", $id);
$stmtGet->execute();
$result = $stmtGet->get_result();
$gambar = null;

if ($row = $result->fetch_assoc()) {
    $gambar = $row['gambar'];
}
$stmtGet->close();

$stmtKategori = $koneksi->prepare("DELETE FROM article_category WHERE artikel_id = ?");
$stmtKategori->bind_param("i", $id);
$stmtKategori->execute();
$stmtKategori->close();

$stmt = $koneksi->prepare("DELETE FROM articles WHERE id = ?");
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Gagal mempersiapkan query']);
    exit;
}

$stmt->bind_param("i", $id);
if ($stmt->execute()) {
    if ($gambar) {
        $pathGambar = __DIR__ . '/../../' . $gambar; 
        if (file_exists($pathGambar)) {
            unlink($pathGambar); 
        }
    }

    echo json_encode(['success' => true, 'message' => 'Artikel dan gambar berhasil dihapus']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $stmt->error]);
}
$stmt->close();
