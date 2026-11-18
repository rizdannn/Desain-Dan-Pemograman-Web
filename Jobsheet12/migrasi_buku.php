<?php

require __DIR__ . '/includes/koneksi.php';

if (php_sapi_name() !== 'cli') {
    header('Content-Type: text/plain; charset=utf-8');
}

$file = __DIR__ . '/data/buku.json';
if (!file_exists($file)) {
    die("File tidak ditemukan: $file\n");
}

$daftarBuku = json_decode(file_get_contents($file), true);
if (!is_array($daftarBuku)) {
    die("Isi buku.json bukan JSON yang valid.\n");
}

$stmt = $pdo->prepare(
    "INSERT INTO buku (judul, pengarang, tahun, isbn, stok, kategori)
     SELECT :judul, :pengarang, :tahun, :isbn, :stok, :kategori
     WHERE NOT EXISTS (
         SELECT 1 FROM buku WHERE judul = :judul2 AND pengarang = :pengarang2
     )"
);

$masuk = 0;
$dilewati = 0;

try {
    $pdo->beginTransaction();

    foreach ($daftarBuku as $item) {
        $stmt->execute([
            'judul'      => $item['judul'],
            'pengarang'  => $item['pengarang'],
            'tahun'      => (int) $item['tahun'],
            'isbn'       => $item['isbn'] ?? null,       
            'stok'       => (int) $item['stok'],
            'kategori'   => strtolower($item['kategori'] ?? ''),
            'judul2'     => $item['judul'],
            'pengarang2' => $item['pengarang'],
        ]);

        if ($stmt->rowCount() > 0) {
            $masuk++;
        } else {
            $dilewati++;
        }
    }

    $pdo->commit();
} catch (PDOException $e) {
    $pdo->rollBack();
    die("Migrasi gagal, semua perubahan dibatalkan: " . $e->getMessage() . "\n");
}

echo "Migrasi selesai.\n";
echo "Berhasil dimasukkan : $masuk buku\n";
echo "Dilewati (sudah ada): $dilewati buku\n";