<?php
session_start();
require __DIR__ . '/../includes/koneksi.php';

$nama = trim($_POST['nama'] ?? '');
$noAnggota = trim($_POST['no_anggota'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');
$noHp = trim($_POST['no_hp'] ?? '');

$errors = [];
if ($nama === '') {
    $errors[] = "Nama wajib diisi.";
}
if ($noAnggota === '') {
    $errors[] = "No. Anggota wajib diisi.";
}

if (!empty($errors)) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => implode(' ', $errors)];
    header('Location: tambah.php');
    exit;
}

$stmt = $pdo->prepare(
    "INSERT INTO anggota (nama, no_anggota, alamat, no_hp)
     VALUES (:nama, :no_anggota, :alamat, :no_hp)
     RETURNING id"
);

try {
    $stmt->execute([
        'nama' => $nama,
        'no_anggota' => $noAnggota,
        'alamat' => $alamat,
        'no_hp' => $noHp,
    ]);
} catch (PDOException $e) {
    // SQLSTATE 23505 = unique_violation (no_anggota sudah ada)
    if ($e->getCode() === '23505') {
        $pesan = 'No. Anggota sudah dipakai, gunakan nomor lain.';
    } else {
        // Error lain: jangan tampilkan pesan mentah ke pengguna
        error_log('Gagal menambah anggota: ' . $e->getMessage());
        $pesan = 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.';
    }
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => $pesan];
    header('Location: tambah.php');
    exit;
}

$_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Anggota berhasil ditambahkan.'];
header('Location: list.php');
exit;