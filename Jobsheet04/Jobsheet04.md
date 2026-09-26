## Aktor

- **Tamu**: hanya bisa melihat katalog buku (Beranda, Daftar Buku) tanpa login.
- **Petugas**: login untuk mengakses seluruh fitur CRUD dan transaksi peminjaman.

## User Flow — Peminjaman Buku

+---------------------+
|   Petugas Login     |
+---------------------+
          |
          v
+---------------------+
|      Dashboard      |
+---------------------+
          |
          v
+---------------------------+
| Pilih menu "Peminjaman    |
| Baru"                     |
+---------------------------+
          |
          v
+----------------------+
|   Pilih Anggota      |
+----------------------+
          |
          v
+---------------------------+
| Pilih Buku (stok > 0)     |
+---------------------------+
          |
          v
+-----------------------+
|       Simpan          |
+-----------------------+
          |
          v
+---------------------------+
| Stok buku berkurang 1     |
+---------------------------+
          |
          v
+-----------------------+
|  Kembali ke Dashboard |
+-----------------------+

## User Flow — Pengembalian Buku

+---------------------+
|      Dashboard      |
+---------------------+
          |
          v
+---------------------------+
| Menu "Pengembalian"       |
+---------------------------+
          |
          v
+----------------------------------+
|     Cari transaksi aktif         |
|        (anggota/buku)            |
+----------------------------------+
          |
          v
+---------------------------+
| Tandai "Dikembalikan"     |
+---------------------------+
          |
          v
+---------------------------+
| Stok buku bertambah 1     |
+---------------------------+
          |
          v
+-----------------------+
|  Kembali ke Dashboard |
+-----------------------+

## Wireframe: Halaman Login

+--------------------------------------+
|             SIMPUS-Mini              |
|--------------------------------------|
|                                      |
|         [ Login Petugas ]            |
|                                      |
|   Username : [______________]        |
|   Password : [______________]        |
|                                      |
|             [ Masuk ]                |
|                                      |
|   Belum punya akun? Daftar di sini   |
+--------------------------------------+


## Wireframe: Dashboard Petugas

+-----------------------------------------------------------------------------+
| SIMPUS-Mini  Beranda | Buku | Anggota | Peminjaman |  (Nama Petugas) Logout |
|-----------------------------------------------------------------------------|
|  [Total Buku]   [Total Anggota]   [Sedang Dipinjam]                         |
|                                                                             |
|  Aksi Cepat:                                                                |
|  [ + Peminjaman Baru ]   [ + Pengembalian ]                                 |
|                                                                             |
|  Transaksi Terbaru                                                          |
|  ---------------------------------------------------------------------------|
|  Anggota             | Buku | Tgl Pinjam | Status                           |
+-----------------------------------------------------------------------------+


## Wireframe: Form Peminjaman

+---------------------------------------+
|        Form Peminjaman Buku           |
|---------------------------------------|
|  Anggota : [ dropdown pilih anggota ] |
|  Buku    : [ dropdown, hanya stok>0 ] |
|  Tanggal Pinjam : [ auto: hari ini ]  |
|                                       |
|         [ Simpan Peminjaman ]         |
+---------------------------------------+ 


## Wireframe: Form Pengembalian

+---------------------------------------------+
|            Pengembalian Buku                |
|---------------------------------------------|
|  Cari transaksi aktif:                      |
|  [ nama anggota / judul buku ______ ]       |
|                                             |
|  Anggota | Buku | Tgl Pinjam | [Kembalikan] |
+---------------------------------------------+


## Wireframe: Riwayat Peminjaman per Anggota

+------------------------------------------------+
|   Riwayat Peminjaman — Siti Aminah             |
|------------------------------------------------|
|  Buku             | Pinjam | Kembali | Status  |
|  Laskar Pelangi   | 01/07  | 10/07   | Selesai |
|  Bumi Manusia     | 15/07  | -       | Dipinjam|
+------------------------------------------------+ 


## Konsistensi dengan Desain yang Sudah Berjalan

- Warna aksen, tipografi navbar, dan gaya tabel/kartu mengikuti `assets/css/style.css` yang sudah dibangun sejak Jobsheet 2-3.
- Navbar akan ditambah menu Peminjaman dan indikator status login (nama petugas / tombol Logout) mulai implementasi di Jobsheet 10.
- Komponen UI (tabel, tombol, form, card) konsisten dengan desain yang sudah berjalan untuk menjaga pengalaman pengguna yang seragam.
- Tipografi yang digunakan tetap mengikuti style yang ada agar tampilan antarmuka tetap selaras dan profesional.

## Validasi & Edge Case

- Buku dengan stok 0 tidak boleh dipilih di form peminjaman.
- Anggota dengan tunggakan/terlambat divalidasi saat peminjaman (implementasi di Jobsheet 12).
- Stok buku otomatis berkurang saat peminjaman dan bertambah saat pengembalian.
- Transaksi hanya dapat dikembalikan jika status masih "Dipinjam".
- Hanya petugas yang login yang dapat mengakses fitur CRUD dan transaksi.

> **Catatan:** Seluruh wireframe ini adalah rancangan antarmuka. Implementasi akan dilakukan mulai Jobsheet 5 dan seterusnya.

# 6.4 Ide Latihan Tambahan (Opsional)


## Latihan 1: Wireframe Halaman Registrasi Anggota Baru

+--------------------------------------+
|             SIMPUS-Mini              |
|--------------------------------------|
|                                      |
|      [ Registrasi Anggota Baru ]     |
|                                      |
|   Nama Lengkap : [______________]    |
|   Email        : [______________]    |
|   No. Telepon  : [______________]    |
|   Alamat       : [______________]    |
|                                      |
|            [ Daftar ]                |
|                                      |
|   Sudah punya akun? Login di sini    |
+--------------------------------------+

## Latihan 2: User Flow — Cari Anggota dengan Tunggakan Terlambat

+---------------------+
|   Petugas Login     |
+---------------------+
          |
          v
+---------------------+
|      Dashboard      |
+---------------------+
          |
          v
+----------------------------------+
| Pilih menu "Anggota"             |
+----------------------------------+
          |
          v
+----------------------------------+
| Filter: "Tunggakan Terlambat"    |
+----------------------------------+
          |
          v
+----------------------------------+
| Sistem cek tanggal jatuh tempo   |
| tiap transaksi aktif             |
+----------------------------------+
          |
          v
+----------------------------------+
| Tampilkan daftar anggota yang    |
| terlambat mengembalikan          |
+----------------------------------+
          |
          v
+-----------------------+
|  Kembali ke Dashboard |
+-----------------------+


## Latihan 3: Edge Case Tambahan

1. **Peminjaman buku yang sama ke anggota yang sama, dua kali berturut-turut**
   Kalau anggota masih meminjam satu eksemplar buku (status "Dipinjam"), sistem harus mencegah anggota yang sama meminjam judul buku itu lagi sebelum eksemplar sebelumnya dikembalikan mencegah data ganda yang membingungkan saat pelacakan status.

2. **Anggota mencoba menghapus data saat masih punya transaksi aktif**
   Kalau anggota masih punya buku yang sedang dipinjam (status "Dipinjam"), data anggota tersebut sebaiknya tidak bisa dihapus dulu  supaya riwayat transaksi tidak kehilangan referensi anggotanya.

3. **Buku dihapus padahal sedang dipinjam**
   Mirip kasus di atas: buku yang statusnya sedang dipinjam anggota sebaiknya tidak bisa dihapus dari katalog, karena akan membuat data transaksi menggantung (merujuk ke buku yang sudah tidak ada).

4. **Petugas mencoba mengembalikan transaksi yang sudah "Selesai"**
   Tombol kembalikan seharusnya tidak muncul atau tidak aktif untuk transaksi yang statusnya sudah selesai mencegah stok bertambah dua kali untuk transaksi yang sama.

