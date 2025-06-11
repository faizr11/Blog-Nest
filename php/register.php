<?php
include 'koneksi.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari input
    $nama     = trim($_POST['nama'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Validasi input kosong
    if (empty($nama) || empty($email) || empty($password)) {
        echo json_encode([
            'success' => false,
            'message' => 'Nama, email, dan password wajib diisi.'
        ]);
        exit;
    }

    // Cek apakah email sudah digunakan
    $stmt = $koneksi->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Email sudah terdaftar.'
        ]);
        exit;
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Data default
    $role = 'penulis';
    $status = 'aktif';
    $foto_profil = null; // default NULL jika tidak upload foto

    // Simpan user baru ke database
    $stmt = $koneksi->prepare("INSERT INTO users (nama, email, password, role, status, foto_profil) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $nama, $email, $hashedPassword, $role, $status, $foto_profil);

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Registrasi berhasil. Silakan login.',
            'redirect' => 'login.php'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Registrasi gagal. Silakan coba lagi.'
        ]);
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode([
        'success' => false,
        'message' => 'Metode tidak diizinkan.'
    ]);
}
?>
