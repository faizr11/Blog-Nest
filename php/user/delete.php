<?php
session_start();
require __DIR__ . '/../koneksi.php';
header('Content-Type: application/json');


// Validasi method POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

// Validasi hanya admin yang boleh hapus (opsional)
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit;
}

$id = $_POST['id'] ?? null;

if (!$id || !is_numeric($id)) {
    echo json_encode(['success' => false, 'message' => 'Invalid user ID.']);
    exit;
}

// Ambil data user untuk hapus foto jika ada
$queryOld = $koneksi->prepare("SELECT foto_profil FROM users WHERE id = ?");
$queryOld->bind_param("i", $id);
$queryOld->execute();
$result = $queryOld->get_result();
$user = $result->fetch_assoc();
$queryOld->close();

if (!$user) {
    echo json_encode(['success' => false, 'message' => 'User not found.']);
    exit;
}

// Hapus foto jika ada
if (!empty($user['foto_profil'])) {
    $path = __DIR__ . '/../../' . $user['foto_profil'];
    if (file_exists($path)) {
        unlink($path);
    }
}

// 🔥 Tambahan: Hapus artikel milik user berdasarkan penulis_id
$hapusArtikel = $koneksi->prepare("DELETE FROM articles WHERE penulis_id = ?");
$hapusArtikel->bind_param("i", $id);
$hapusArtikel->execute();
$hapusArtikel->close();

// Hapus user dari database
$stmt = $koneksi->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete user.']);
}
?>
