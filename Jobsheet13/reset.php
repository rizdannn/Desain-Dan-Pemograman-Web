<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require __DIR__ . '/includes/csrf.php';

// Hanya terima permintaan POST (tombol di form), bukan klik link biasa
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

csrf_verify();

// 1. Kosongkan semua data di $_SESSION (di memori request ini)
$_SESSION = [];

// 2. Hapus cookie PHPSESSID di browser
if (ini_get('session.use_cookies')) {
    $p = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $p['path'], $p['domain'], $p['secure'], $p['httponly']);
}

// 3. Hancurkan session di server
session_destroy();

// 4. Mulai session baru supaya bisa menyimpan flash message
session_start();
$_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Semua data berhasil direset.'];

header('Location: index.php');
exit;
