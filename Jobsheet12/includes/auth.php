<?php
// Guard clause: di-include di baris paling atas setiap halaman yang
// membutuhkan login (sebelum header.php mengeluarkan output apa pun),
// agar header('Location: ...') masih bisa dipanggil.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kalau sesi sudah hilang tapi ada cookie "ingat saya" yang valid, login otomatis
if (!isset($_SESSION['user_id']) && !empty($_COOKIE['ingat_saya'])) {
    [$id, $tanda] = array_pad(explode('.', $_COOKIE['ingat_saya'], 2), 2, '');

    if (hash_equals(hash_hmac('sha256', $id, 'kunci-rahasia-simpus'), $tanda)) {
        require __DIR__ . '/koneksi.php';
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => (int) $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['role'] = $user['role'];
        }
    }
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}
