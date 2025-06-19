<?php
session_start();
require __DIR__ . '/../koneksi.php';

if (!isset($_SESSION['id'])) {
    die("Unauthorized access");
}

$id = $_SESSION['id'];
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? null;
$bio = trim($_POST['bio'] ?? '');
$avatarPath = null;

// Validasi input dasar
if ($name === '' || $email === '') {
    die("Name and email are required.");
}

// Ambil data user lama (terutama foto lama)
$queryOld = $koneksi->prepare("SELECT foto_profil FROM users WHERE id = ?");
$queryOld->bind_param("i", $id);
$queryOld->execute();
$resultOld = $queryOld->get_result();
$oldData = $resultOld->fetch_assoc();
$oldAvatar = $oldData['foto_profil'] ?? null;
$queryOld->close();

// Jika ada file gambar diupload
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

        // Hapus foto lama jika ada dan bukan default
        if ($oldAvatar && file_exists(__DIR__ . '/../../' . $oldAvatar)) {
            unlink(__DIR__ . '/../../' . $oldAvatar);
        }
    } else {
        die("Failed to upload image.");
    }
}

// Bangun query UPDATE
$sql = "UPDATE users SET nama = ?, email = ?, bio = ?";
$params = [$name, $email, $bio];
$types = "sss";

// Tambah password jika diisi
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

$stmt = $koneksi->prepare($sql);
$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    $_SESSION['nama'] = $name;
    $_SESSION['email'] = $email;
    $_SESSION['bio'] = $bio;
    if ($avatarPath) {
        $_SESSION['foto_profil'] = $avatarPath;
    }

    header("Location: ../../profile.php?success=1");
    exit;
} else {
    die("Failed to update profile: " . $stmt->error);
}
?>
