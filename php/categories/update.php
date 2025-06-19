<?php
require __DIR__ . '/../koneksi.php';

$id = $_POST['id'] ?? null;
$nama = trim($_POST['name'] ?? '');


$stmt = $koneksi->prepare("UPDATE category SET nama = ? WHERE id = ?");
if (!$stmt) {
    die("Prepare failed: " . $koneksi->error);
}

$stmt->bind_param("si", $nama, $id);

if ($stmt->execute()) {
    header("Location: ../../admin/categories.php?success=1");
    exit;
} else {
    die("Gagal memperbarui kategori: " . $stmt->error);
}
?>
