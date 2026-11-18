<?php
require __DIR__ . '/../includes/auth.php';
$page_title = "Daftar Anggota";
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

    $hitung = $pdo->prepare("SELECT COUNT(*) FROM anggota WHERE nama ILIKE :kw1 OR no_anggota ILIKE :kw2");
    $hitung->execute(['kw1' => $like, 'kw2' => $like]);
    $totalRows = (int) $hitung->fetchColumn();

    $stmt = $pdo->prepare(
        "SELECT * FROM anggota
         WHERE nama ILIKE :kw1 OR no_anggota ILIKE :kw2
         ORDER BY id DESC LIMIT :limit OFFSET :offset"
    );
    $stmt->bindValue('kw1', $like);
    $stmt->bindValue('kw2', $like);
} else {
    $totalRows = (int) $pdo->query("SELECT COUNT(*) FROM anggota")->fetchColumn();
    $stmt = $pdo->prepare("SELECT * FROM anggota ORDER BY id DESC LIMIT :limit OFFSET :offset");
}
$stmt->bindValue('limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue('offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$daftarAnggota = $stmt->fetchAll(PDO::FETCH_ASSOC);
$totalPages = max(1, (int) ceil($totalRows / $perPage));
$qs = $keyword !== '' ? '&q=' . urlencode($keyword) : '';
?>
        <section>
            <h2>Daftar Anggota</h2>

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
                <label for="search-input">Cari Nama / No. Anggota</label>
                <input type="text" id="search-input" name="q"
                       value="<?php echo e($keyword); ?>"
                       placeholder="Ketik nama atau nomor anggota, lalu tekan Enter...">
                <?php if ($keyword !== ''): ?>
                    <a href="list.php">Reset pencarian</a>
                <?php endif; ?>
            </form>

            <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>No. Anggota</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>No. HP</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($daftarAnggota)): ?>
                    <tr>
                        <td colspan="5">
                            <?php if ($keyword !== ''): ?>
                                Tidak ada anggota yang cocok dengan "<?php echo e($keyword); ?>".
                            <?php else: ?>
                                Belum ada data anggota. Silakan tambah lewat menu "Tambah Anggota".
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($daftarAnggota as $anggota): ?>
                        <tr>
                            <td><?php echo e($anggota['no_anggota']); ?></td>
                            <td><?php echo e($anggota['nama']); ?></td>
                            <td><?php echo e($anggota['alamat']); ?></td>
                            <td><?php echo e($anggota['no_hp']); ?></td>
                            <td>
                                <a href="edit.php?id=<?php echo (int) $anggota['id']; ?>" class="btn-edit">Edit</a>
                                <form method="post" action="hapus.php" style="display:inline;"
                                      onsubmit="return confirm('Yakin ingin menghapus anggota &quot;<?php echo e(addslashes($anggota['nama'])); ?>&quot;?');">
                                    <input type="hidden" name="id" value="<?php echo (int) $anggota['id']; ?>">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit">Hapus</button>
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
