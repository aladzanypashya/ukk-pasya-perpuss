-- Jalankan SEKALI di phpMyAdmin (database db_perpustakaan) sebelum memakai fitur pengajuan.

-- 1) Kolom status dibuat VARCHAR supaya bisa menampung: diproses, dipinjam, ditolak, sudah dikembalikan
ALTER TABLE peminjaman MODIFY status VARCHAR(30) NOT NULL DEFAULT 'diproses';

-- 2) user_id (petugas) boleh kosong saat pengajuan; terisi otomatis saat admin/petugas menekan ACC
ALTER TABLE peminjaman MODIFY user_id INT NULL;
