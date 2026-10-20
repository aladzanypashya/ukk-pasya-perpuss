-- Jalankan sekali di database db_perpustakaan (phpMyAdmin > tab SQL)

-- 1) Kolom password perlu panjang agar muat password hash dari halaman register
ALTER TABLE anggota MODIFY password VARCHAR(255) NOT NULL;
ALTER TABLE user    MODIFY password VARCHAR(255) NOT NULL;

-- 2) Kolom foto buku menyimpan nama file
ALTER TABLE buku MODIFY gambar VARCHAR(255) NOT NULL DEFAULT '';

-- 3) Pastikan anggota_id terisi otomatis (dibutuhkan register).
--    Jika sudah AUTO_INCREMENT dan muncul error, baris ini boleh dilewati.
ALTER TABLE anggota MODIFY anggota_id INT NOT NULL AUTO_INCREMENT;

-- 4) (Opsional) akun admin awal jika tabel user masih kosong
-- INSERT INTO user (nama, username, password, level) VALUES ('Administrator', 'admin', 'admin123', 'admin');
