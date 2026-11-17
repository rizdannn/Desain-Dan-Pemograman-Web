// Fungsi generik: ambil data dari data/<namaFile> lalu render ke tabel.
// namaFile   : string, contoh "buku.json"
// daftarKunci: array nama kunci sesuai urutan kolom, contoh ["judul", "pengarang", "tahun", "stok"]
async function muatTabel(namaFile, daftarKunci) {
    const tbody = document.querySelector(".table-responsive table tbody");
    const loading = document.getElementById("loading-indicator");
    if (!tbody) return;

    loading.style.display = "block";
    tbody.innerHTML = "";

    try {
        // simulasi delay jaringan agar loading indicator terlihat
        await new Promise((resolve) => setTimeout(resolve, 300));

        const res = await fetch("../data/" + namaFile);
        if (!res.ok) {
            throw new Error("Gagal mengambil data (status " + res.status + ")");
        }
        const daftarData = await res.json();

        daftarData.forEach(function (item) {
            const tr = document.createElement("tr");

            // satu <td> untuk setiap kunci
            daftarKunci.forEach(function (kunci) {
                const td = document.createElement("td");
                td.textContent = item[kunci];
                tr.appendChild(td);
            });

            // kolom Aksi
            const tdAksi = document.createElement("td");
            tdAksi.innerHTML =
                "<button type=\"button\">Edit</button> " +
                "<button type=\"button\" class=\"btn-hapus\">Hapus</button>";
            tr.appendChild(tdAksi);

            tbody.appendChild(tr);
        });
    } catch (err) {
        // colspan menyesuaikan jumlah kolom (kunci + 1 kolom Aksi)
        tbody.innerHTML =
            "<tr><td colspan=\"" + (daftarKunci.length + 1) + "\">Gagal memuat data: " +
            err.message + "</td></tr>";
    } finally {
        loading.style.display = "none";
    }
}