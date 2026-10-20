<?php
// helpers.php - fungsi bantu yang dipakai register.php (dan boleh dipakai halaman lain nanti).

// Info satu kolom dari database (dipakai untuk mendeteksi apakah anggota_id AUTO_INCREMENT)
function info_kolom($conn, $tabel, $kolom) {
    $t = mysqli_real_escape_string($conn, $tabel);
    $k = mysqli_real_escape_string($conn, $kolom);
    $q = mysqli_query($conn, "SELECT COLUMN_TYPE, IS_NULLABLE, EXTRA FROM information_schema.COLUMNS
                              WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = '$t' AND COLUMN_NAME = '$k'");
    return $q ? mysqli_fetch_assoc($q) : null;
}

// ID berikutnya untuk tabel yang primary key-nya TIDAK auto_increment (harus diisi manual saat INSERT).
// Mengembalikan null kalau kolom memang auto_increment (artinya tidak perlu diisi manual).
function id_berikutnya($conn, $tabel, $kolom_id) {
    $info = info_kolom($conn, $tabel, $kolom_id);
    if ($info && stripos($info['EXTRA'], 'auto_increment') !== false) {
        return null;
    }
    $t = str_replace('`', '``', $tabel);
    $k = str_replace('`', '``', $kolom_id);
    $q = mysqli_query($conn, "SELECT MAX(`$k`) AS m FROM `$t`");
    $r = $q ? mysqli_fetch_assoc($q) : null;
    return ($r && $r['m'] !== null) ? ((int)$r['m'] + 1) : 1;
}

// ----- Unggah foto (opsional, dipakai form register) -----
//
// Kembalian: [ok(bool), nilai]
//   - Ada file valid diunggah -> [true, "nama_file_baru.jpg"]
//   - Tidak ada file diunggah -> [true, ""]
//   - File tidak valid         -> [false, "pesan error"]
function unggah_foto($nama_input, $folder) {
    if (!isset($_FILES[$nama_input]) || $_FILES[$nama_input]['error'] === UPLOAD_ERR_NO_FILE) {
        return [true, ''];
    }
    if ($_FILES[$nama_input]['error'] !== UPLOAD_ERR_OK) {
        return [false, "Gagal mengunggah foto (kode error: " . $_FILES[$nama_input]['error'] . ")."];
    }

    $tmp    = $_FILES[$nama_input]['tmp_name'];
    $ukuran = $_FILES[$nama_input]['size'];

    if ($ukuran > 3 * 1024 * 1024) {
        return [false, "Ukuran foto maksimal 3MB."];
    }

    $info_gambar = @getimagesize($tmp);
    $tipe_diizinkan = [
        IMAGETYPE_JPEG => 'jpg',
        IMAGETYPE_PNG  => 'png',
        IMAGETYPE_GIF  => 'gif',
        IMAGETYPE_WEBP => 'webp',
    ];
    if (!$info_gambar || !isset($tipe_diizinkan[$info_gambar[2]])) {
        return [false, "File harus berupa gambar (JPG, PNG, GIF, atau WEBP)."];
    }
    $ext = $tipe_diizinkan[$info_gambar[2]];

    $folder_tujuan = __DIR__ . "/uploads/$folder";
    if (!is_dir($folder_tujuan)) {
        mkdir($folder_tujuan, 0755, true);
    }

    $nama_file = date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    if (!move_uploaded_file($tmp, "$folder_tujuan/$nama_file")) {
        return [false, "Gagal menyimpan file foto di server."];
    }

    return [true, $nama_file];
}
?>
