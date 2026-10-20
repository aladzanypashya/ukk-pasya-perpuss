<?php
// migrasi_lib.php - menyiapkan tabel peminjaman untuk fitur pengajuan (dipakai migrasi.php & ajukan_peminjaman.php)

function info_kolom($conn, $tabel, $kolom) {
    $t = mysqli_real_escape_string($conn, $tabel);
    $k = mysqli_real_escape_string($conn, $kolom);
    $q = mysqli_query($conn, "SELECT COLUMN_TYPE, IS_NULLABLE FROM information_schema.COLUMNS
                              WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = '$t' AND COLUMN_NAME = '$k'");
    return $q ? mysqli_fetch_assoc($q) : null;
}

// Membuat user_id boleh kosong dengan mempertahankan tipe aslinya (dan menangani foreign key bila ada)
function ubah_user_id($conn, $tipe) {
    if (mysqli_query($conn, "ALTER TABLE peminjaman MODIFY user_id $tipe NULL")) {
        return [true, "Kolom user_id sekarang boleh kosong."];
    }
    $err1 = mysqli_error($conn);

    // Cadangan: ada foreign key -> lepas, ubah kolom mengikuti tipe kolom rujukan, pasang lagi
    $q = mysqli_query($conn, "SELECT k.CONSTRAINT_NAME AS nama, k.REFERENCED_TABLE_NAME AS tabel_ref,
                                     k.REFERENCED_COLUMN_NAME AS kolom_ref, r.DELETE_RULE AS aturan_hapus, r.UPDATE_RULE AS aturan_ubah
                              FROM information_schema.KEY_COLUMN_USAGE k
                              JOIN information_schema.REFERENTIAL_CONSTRAINTS r
                                ON r.CONSTRAINT_SCHEMA = k.CONSTRAINT_SCHEMA
                               AND r.CONSTRAINT_NAME = k.CONSTRAINT_NAME
                               AND r.TABLE_NAME = k.TABLE_NAME
                              WHERE k.TABLE_SCHEMA = DATABASE() AND k.TABLE_NAME = 'peminjaman'
                                AND k.COLUMN_NAME = 'user_id' AND k.REFERENCED_TABLE_NAME IS NOT NULL");
    $fk = $q ? mysqli_fetch_assoc($q) : null;
    if (!$fk) {
        return [false, $err1];
    }

    $ref      = info_kolom($conn, $fk['tabel_ref'], $fk['kolom_ref']);
    $tipe_ref = $ref ? $ref['COLUMN_TYPE'] : $tipe;
    $nm = str_replace('`', '``', $fk['nama']);
    $tr = str_replace('`', '``', $fk['tabel_ref']);
    $kr = str_replace('`', '``', $fk['kolom_ref']);

    $langkah = [
        "ALTER TABLE peminjaman DROP FOREIGN KEY `$nm`",
        "ALTER TABLE peminjaman MODIFY user_id $tipe_ref NULL",
        "ALTER TABLE peminjaman ADD CONSTRAINT `$nm` FOREIGN KEY (user_id) REFERENCES `$tr`(`$kr`) ON DELETE {$fk['aturan_hapus']} ON UPDATE {$fk['aturan_ubah']}",
    ];
    foreach ($langkah as $sql) {
        if (!mysqli_query($conn, $sql)) {
            return [false, "Gagal pada: $sql -> " . mysqli_error($conn)];
        }
    }
    return [true, "Kolom user_id sekarang boleh kosong (foreign key dipasang ulang)."];
}

function jalankan_migrasi($conn) {
    $hasil = [];

    // 1) Kolom status -> VARCHAR supaya bisa menyimpan: diproses, dipinjam, ditolak, sudah dikembalikan
    $st = info_kolom($conn, 'peminjaman', 'status');
    if (!$st) {
        $hasil[] = [false, "Kolom status tidak ditemukan di tabel peminjaman (database: db_perpustakaan)."];
    } else {
        $panjang = preg_match('/^varchar\((\d+)\)/i', $st['COLUMN_TYPE'], $m) ? (int)$m[1] : 0;
        if ($panjang >= 20) {
            $hasil[] = [true, "Kolom status sudah VARCHAR($panjang) - dilewati."];
        } else {
            $null = ($st['IS_NULLABLE'] === 'YES') ? 'NULL' : 'NOT NULL';
            if (mysqli_query($conn, "ALTER TABLE peminjaman MODIFY status VARCHAR(30) $null DEFAULT 'diproses'")) {
                $hasil[] = [true, "Kolom status diubah dari " . $st['COLUMN_TYPE'] . " menjadi VARCHAR(30)."];
            } else {
                $hasil[] = [false, "Gagal mengubah kolom status: " . mysqli_error($conn)];
            }
        }
    }

    // 2) Kolom user_id (petugas) boleh kosong saat pengajuan
    $u = info_kolom($conn, 'peminjaman', 'user_id');
    if (!$u) {
        $hasil[] = [false, "Kolom user_id tidak ditemukan di tabel peminjaman."];
    } elseif ($u['IS_NULLABLE'] === 'YES') {
        $hasil[] = [true, "Kolom user_id sudah boleh kosong - dilewati."];
    } else {
        $hasil[] = ubah_user_id($conn, $u['COLUMN_TYPE']);
    }
    return $hasil;
}
?>
