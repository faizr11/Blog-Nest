<?php
session_start();
require __DIR__ . '/../koneksi.php';

// Validasi method POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

// Validasi hanya admin yang boleh hapus (opsional)
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403); // Forbidden
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

// Hapus user dari database
$stmt = $koneksi->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete user.']);
}
?>
