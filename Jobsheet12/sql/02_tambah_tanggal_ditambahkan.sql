   -- Jobsheet 8: tambah kolom tanggal_ditambahkan di tabel buku
   ALTER TABLE buku
       ADD COLUMN IF NOT EXISTS tanggal_ditambahkan TIMESTAMP DEFAULT NOW();