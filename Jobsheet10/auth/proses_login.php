<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require __DIR__ . '/../includes/koneksi.php';

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
$stmt->execute(['username' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    // Login berhasil: reset hitungan gagal untuk username ini
    unset($_SESSION['gagal'][$username]);

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['nama'] = $user['nama'];
    $_SESSION['role'] = $user['role'];

    if (isset($_POST['ingat_saya'])) {
        $tanda = hash_hmac('sha256', (string) $user['id'], 'kunci-rahasia-simpus');
        setcookie('ingat_saya', $user['id'] . '.' . $tanda, time() + 30 * 86400, '/', '', false, true);
    }

    header('Location: ../index.php');
    exit;
}

// Login gagal: tambah hitungan per username
$_SESSION['gagal'][$username] = ($_SESSION['gagal'][$username] ?? 0) + 1;
$jumlah = $_SESSION['gagal'][$username];

$pesan = 'Username atau password salah.';
if ($jumlah >= 3) {
    $pesan .= " Peringatan: sudah gagal $jumlah kali berturut-turut.";
}

$_SESSION['flash'] = ['type' => 'error', 'pesan' => $pesan];
header('Location: login.php');
exit;