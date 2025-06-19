<?php
require __DIR__ . '/../koneksi.php';

header('Location: ../../admin/categories.php?success=1');

// Ambil dan sanitasi input
$name = trim($_POST['name'] ?? '');

if ($name === '') {
    echo json_encode(['success' => false, 'message' => 'Category name is required.']);
    exit;
}

// Cek apakah kategori sudah ada
$check = $koneksi->prepare("SELECT id FROM category WHERE nama = ?");
$check->bind_param("s", $name);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Category already exists.']);
    exit;
}
$check->close();

// Insert data baru
$stmt = $koneksi->prepare("INSERT INTO category (nama) VALUES (?)");
$stmt->bind_param("s", $name);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Category added successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to add category: ' . $stmt->error]);
}
$stmt->close();
