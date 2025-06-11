<?php
include 'koneksi.php';
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Validasi input kosong
    if (empty($email) || empty($password)) {
        echo json_encode([
            'success' => false,
            'message' => 'Email dan password wajib diisi.'
        ]);
        exit;
    }

    // Gunakan prepared statement untuk keamanan
    $stmt = $koneksi->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['id'] = $user['id'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['status'] = $user['status'];
        $_SESSION['foto_profil'] = $user['foto_profil'];
        $_SESSION['bio'] = $user['bio'];

        echo json_encode([
            'success' => true,
            'message' => 'Login berhasil.',
            'redirect' => 'index.php'
        ]);
    } else {
        echo json_encode([
            'success' => false,

            'message' => 'Email atau password salah.'
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