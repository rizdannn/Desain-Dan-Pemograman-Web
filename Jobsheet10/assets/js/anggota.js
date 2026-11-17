function muatDaftarAnggota() {
    return muatTabel("anggota.json", ["no_anggota", "nama", "alamat", "no_hp"]);
}

document.addEventListener("DOMContentLoaded", muatDaftarAnggota);