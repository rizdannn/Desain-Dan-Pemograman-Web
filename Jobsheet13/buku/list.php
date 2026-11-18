<?php
$page_title = "Daftar Buku";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$keyword = trim($_GET['q'] ?? '');
$perPage = 10;
$page = max(1, (int) ($_GET['page'] ?? 1));
$offset = ($page - 1) * $perPage;

if ($keyword !== '') {
    $like = '%' . $keyword . '%';

    $hitung = $pdo->prepare("SELECT COUNT(*) FROM buku WHERE judul ILIKE :kw1 OR pengarang ILIKE :kw2");
    $hitung->execute(['kw1' => $like, 'kw2' => $like]);
    $totalRows = (int) $hitung->fetchColumn();

    $stmt = $pdo->prepare(
        "SELECT * FROM buku
         WHERE judul ILIKE :kw1 OR pengarang ILIKE :kw2
         ORDER BY id DESC LIMIT :limit OFFSET :offset"
    );
    $stmt->bindValue('kw1', $like);
    $stmt->bindValue('kw2', $like);
} else {
    $totalRows = (int) $pdo->query("SELECT COUNT(*) FROM buku")->fetchColumn();
    $stmt = $pdo->prepare("SELECT * FROM buku ORDER BY id DESC LIMIT :limit OFFSET :offset");
}
$stmt->bindValue('limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue('offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$daftarBuku = $stmt->fetchAll(PDO::FETCH_ASSOC);
$totalPages = max(1, (int) ceil($totalRows / $perPage));
$qs = $keyword !== '' ? '&q=' . urlencode($keyword) : '';
?>
        <section>
            <h2>Daftar Buku</h2>

            <div style="margin-bottom: 1rem;">
                <form method="post" action="<?php echo $base; ?>reset.php"
                        onsubmit="return confirm('Yakin hapus SEMUA data buku dan anggota?');">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn-hapus">Reset Data</button>
                </form>
            </div>

            <?php if ($flash): ?>
                <p class="flash flash-<?php echo e($flash['type']); ?>"><?php echo e($flash['pesan']); ?></p>
            <?php endif; ?>

            <form method="get" action="list.php" class="search-box">
                <label for="search-input">Cari Judul / Pengarang</label>
                <input type="text" id="search-input" name="q"
                       value="<?php echo e($keyword); ?>"
                       placeholder="Ketik judul atau pengarang, lalu tekan Enter...">
                <?php if ($keyword !== ''): ?>
                    <a href="list.php">Reset pencarian</a>
                <?php endif; ?>
            </form>

            <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Pengarang</th>
                        <th>Kategori</th>
                        <th>Tahun</th>
                        <th>Stok</th>
                        <th>Ditambahkan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($daftarBuku)): ?>
                    <tr>
                        <td colspan="7">
                            <?php if ($keyword !== ''): ?>
                                Tidak ada buku dengan judul atau pengarang yang mengandung "<?php echo e($keyword); ?>".
                            <?php else: ?>
                                Belum ada data buku. Silakan tambah lewat menu "Tambah Buku".
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($daftarBuku as $buku): ?>
                        <tr>
                            <td><?php echo e($buku['judul']); ?></td>
                            <td><?php echo e($buku['pengarang']); ?></td>
                            <?php
                            $daftarKategori = [
                                'fiksi'     => 'Fiksi',
                                'non-fiksi' => 'Non-Fiksi',
                                'referensi' => 'Referensi',
                            ];
                            $labelKategori = $daftarKategori[$buku['kategori'] ?? ''] ?? '-';
                            ?>
                            <td><?php echo e($labelKategori); ?></td>
                            <td><?php echo e($buku['tahun']); ?></td>
                            <?php
                            $stok = (int) $buku['stok'];
                            if ($stok <= 0) {
                                $kelasBadge = 'badge-habis';
                                $labelBadge = 'Habis';
                            } elseif ($stok <= 2) {
                                $kelasBadge = 'badge-menipis';
                                $labelBadge = 'Menipis';
                            } else {
                                $kelasBadge = 'badge-tersedia';
                                $labelBadge = 'Tersedia';
                            }
                            ?>
                            <td>
                                <span class="badge <?php echo $kelasBadge; ?>"><?php echo $stok . ' &middot; ' . $labelBadge; ?></span>
                            </td>
                            <td>
                                <?php
                                echo !empty($buku['tanggal_ditambahkan'])
                                    ? date('d/m/Y H:i', strtotime($buku['tanggal_ditambahkan']))
                                    : '-';
                                ?>
                            </td>
                            <td>
                                <a href="edit.php?id=<?php echo (int) $buku['id']; ?>" class="btn-edit">Edit</a>
                                <form method="post" action="hapus.php" style="display:inline;"
                                      onsubmit="return confirm('Yakin ingin menghapus buku &quot;<?php echo e(addslashes($buku['judul'])); ?>&quot;?');">
                                    <input type="hidden" name="id" value="<?php echo (int) $buku['id']; ?>">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn-danger" 
                                    style="background-color:#d9534f; color:#fff; padding:0.35rem 0.75rem; font-size:0.85rem; border:none; border-radius:4px; cursor:pointer;">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            </div>

            <?php if ($totalPages > 1): ?>
            <nav class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1 . $qs; ?>">&laquo; Sebelumnya</a>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?php echo $i . $qs; ?>" class="<?php echo $i === $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>
                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?php echo $page + 1 . $qs; ?>">Berikutnya &raquo;</a>
                <?php endif; ?>
            </nav>
            <?php endif; ?>
        </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
