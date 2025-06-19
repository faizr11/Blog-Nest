<?php
session_start();
require __DIR__ . '/../koneksi.php';

// Validasi hanya admin (opsional bisa pakai $_SESSION['role'] jika tersedia)
// if ($_SESSION['role'] !== 'admin') {
//     die("Unauthorized access");
// }

$id = $_POST['id'] ?? null;
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? null;
$bio = trim($_POST['bio'] ?? '');
$status = $_POST['status'] ?? 'aktif';
$avatarPath = null;

// Validasi
if (!$id || $name === '' || $email === '') {
    die("ID, name, and email are required.");
}

// Cek email apakah digunakan oleh user lain
$cekEmail = $koneksi->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
$cekEmail->bind_param("si", $email, $id);
$cekEmail->execute();
$cekEmail->store_result();
if ($cekEmail->num_rows > 0) {
    die("Email sudah digunakan oleh user lain.");
}
$cekEmail->close();

// Ambil data user lama
$queryOld = $koneksi->prepare("SELECT foto_profil FROM users WHERE id = ?");
$queryOld->bind_param("i", $id);
$queryOld->execute();
$resultOld = $queryOld->get_result();
$oldData = $resultOld->fetch_assoc();
$oldAvatar = $oldData['foto_profil'] ?? null;
$queryOld->close();

// Upload avatar baru jika ada
if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
    $fileTmp = $_FILES['avatar']['tmp_name'];
    $fileName = basename($_FILES['avatar']['name']);
    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];

    if (!in_array($ext, $allowed)) {
        die("Invalid file type.");
    }

    $uploadDir = __DIR__ . '/../../uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $newFileName = uniqid('profile_', true) . '.' . $ext;
    $uploadPath = $uploadDir . $newFileName;
    if (move_uploaded_file($fileTmp, $uploadPath)) {
        $avatarPath = 'uploads/' . $newFileName;

        if ($oldAvatar && file_exists(__DIR__ . '/../../' . $oldAvatar)) {
            unlink(__DIR__ . '/../../' . $oldAvatar);
        }
    } else {
        die("Failed to upload image.");
    }
}

// Bangun query UPDATE
$sql = "UPDATE users SET nama = ?, email = ?, bio = ?, status = ?";
$params = [$name, $email, $bio, $status];
$types = "ssss";

// Tambah password jika ada
if (!empty($password)) {
    $sql .= ", password = ?";
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $params[] = $hashedPassword;
    $types .= "s";
}

// Tambah foto jika diupload
if ($avatarPath) {
    $sql .= ", foto_profil = ?";
    $params[] = $avatarPath;
    $types .= "s";
}

$sql .= " WHERE id = ?";
$params[] = $id;
$types .= "i";

// Eksekusi
$stmt = $koneksi->prepare($sql);
$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    header("Location: ../../admin/authors.php?success=1");
    exit;
} else {
    die("Failed to update user: " . $stmt->error);
}
?>
