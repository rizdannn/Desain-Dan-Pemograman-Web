// Pembungkus tipis: tetap mempertahankan nama muatDaftarBuku() dari Nomor 1
function muatDaftarBuku() {
    return muatTabel("buku.json", ["judul", "pengarang", "tahun", "stok", "kategori"]);
}

document.addEventListener("DOMContentLoaded", function () {
    muatDaftarBuku();

    const btnMuatUlang = document.getElementById("btn-muat-ulang");
    if (btnMuatUlang) {
        btnMuatUlang.addEventListener("click", async function () {
            btnMuatUlang.disabled = true;
            await muatDaftarBuku();
            btnMuatUlang.disabled = false;
        });
    }
});