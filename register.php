<?php
include "connection.php";
include "auth.php";
include "helpers.php";
mysqli_report(MYSQLI_REPORT_OFF);

// Kalau sudah login, tidak perlu daftar lagi
if (sudah_login()) {
    header("Location: dashboard.php");
    exit;
}

$error  = "";
$sukses = false;
$input  = $_POST; // untuk mengisi ulang form kalau ada error

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode_anggota  = trim($_POST['kode_anggota'] ?? '');
    $nama          = trim($_POST['nama'] ?? '');
    $password      = $_POST['password'] ?? '';
    $konfirmasi    = $_POST['konfirmasi'] ?? '';
    $jenis_kelamin = $_POST['jenis_kelamin'] ?? '';
    $tempat_lahir  = trim($_POST['tempat_lahir'] ?? '');
    $tanggal_lahir = $_POST['tanggal_lahir'] ?? '';
    $telpon        = trim($_POST['telpon'] ?? '');
    $alamat        = trim($_POST['alamat'] ?? '');

    if ($kode_anggota === '' || $nama === '' || $password === '' || $jenis_kelamin === '') {
        $error = "Kode anggota, nama, password, dan jenis kelamin wajib diisi.";
    } elseif (strlen($password) < 6) {
        $error = "Password minimal 6 karakter.";
    } elseif ($password !== $konfirmasi) {
        $error = "Konfirmasi password tidak sama.";
    } else {
        // Kode anggota harus unik (belum ada di database)
        $cek = mysqli_prepare($conn, "SELECT anggota_id FROM anggota WHERE kode_anggota = ? LIMIT 1");
        mysqli_stmt_bind_param($cek, "s", $kode_anggota);
        mysqli_stmt_execute($cek);
        mysqli_stmt_store_result($cek);

        if (mysqli_stmt_num_rows($cek) > 0) {
            $error = "Kode anggota \"$kode_anggota\" sudah terdaftar. Gunakan kode lain atau login.";
        } else {
            [$ok_foto, $foto] = unggah_foto('foto', 'anggota');
            if (!$ok_foto) {
                $error = $foto;
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $id   = id_berikutnya($conn, 'anggota', 'anggota_id'); // null kalau kolomnya AUTO_INCREMENT

                $kolom = "kode_anggota, nama, password, jenis_kelamin, tempat_lahir, tanggal_lahir, telpon, alamat, foto";
                $tanda = "sssssssss";
                $nilai = [$kode_anggota, $nama, $hash, $jenis_kelamin, $tempat_lahir, $tanggal_lahir, $telpon, $alamat, $foto];

                if ($id !== null) {
                    $kolom = "anggota_id, $kolom";
                    $tanda = "i$tanda";
                    array_unshift($nilai, $id);
                }

                $placeholder = implode(',', array_fill(0, count($nilai), '?'));
                $stmt = mysqli_prepare($conn, "INSERT INTO anggota ($kolom) VALUES ($placeholder)");
                mysqli_stmt_bind_param($stmt, $tanda, ...$nilai);

                if (mysqli_stmt_execute($stmt)) {
                    $sukses = true;
                } else {
                    $error = "Gagal mendaftar: " . mysqli_stmt_error($stmt);
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Anggota - Perpustakaan</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 24px 0; }
        .box { background: #fff; width: 100%; max-width: 440px; padding: 28px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,.12); }
        h2 { text-align: center; color: navy; margin: 0 0 4px; }
        .sub { text-align: center; color: #6b7280; font-size: 13px; margin: 0 0 18px; }
        label { display: block; margin: 12px 0 4px; font-size: 14px; font-weight: bold; }
        input, select, textarea { width: 100%; padding: 9px; border: 1px solid #bbb; border-radius: 4px; font-size: 14px; }
        .dua-kolom { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        button { width: 100%; margin-top: 20px; padding: 10px; background: #007BFF; color: #fff; border: 0; border-radius: 4px; font-size: 15px; cursor: pointer; }
        button:hover { background: #0062cc; }
        .error { background: #f8d7da; color: #842029; padding: 9px; border-radius: 4px; font-size: 14px; margin-bottom: 8px; }
        .sukses { background: #d1e7dd; color: #0f5132; padding: 14px; border-radius: 4px; font-size: 14px; text-align: center; }
        .balik { display: block; text-align: center; margin-top: 16px; font-size: 14px; color: #007BFF; text-decoration: none; }
        .catatan { font-size: 12px; color: #6b7280; margin-top: 4px; }
    </style>
</head>
<body>
<div class="box">
    <h2>Daftar Akun Anggota</h2>
    <p class="sub">Pendaftaran ini khusus untuk anggota perpustakaan. Akun admin/petugas dibuat oleh admin.</p>

    <?php if ($sukses) { ?>
        <div class="sukses">
            Pendaftaran berhasil! Silakan login menggunakan kode anggota dan password Anda.
        </div>
        <a class="balik" href="login.php">&larr; Ke halaman login</a>
    <?php } else { ?>

        <?php if ($error) { ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php } ?>

        <form method="post" enctype="multipart/form-data" autocomplete="off">
            <label for="kode_anggota">Kode Anggota</label>
            <input type="text" name="kode_anggota" id="kode_anggota" value="<?php echo htmlspecialchars($input['kode_anggota'] ?? ''); ?>" required>

            <label for="nama">Nama Lengkap</label>
            <input type="text" name="nama" id="nama" value="<?php echo htmlspecialchars($input['nama'] ?? ''); ?>" required>

            <div class="dua-kolom">
                <div>
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required>
                    <div class="catatan">Minimal 6 karakter</div>
                </div>
                <div>
                    <label for="konfirmasi">Konfirmasi</label>
                    <input type="password" name="konfirmasi" id="konfirmasi" required>
                </div>
            </div>

            <label for="jenis_kelamin">Jenis Kelamin</label>
            <select name="jenis_kelamin" id="jenis_kelamin" required>
                <option value="">-- Pilih Jenis Kelamin --</option>
                <option value="pria"  <?php if (($input['jenis_kelamin'] ?? '') === 'pria')  echo 'selected'; ?>>Pria</option>
                <option value="wanita" <?php if (($input['jenis_kelamin'] ?? '') === 'wanita') echo 'selected'; ?>>Wanita</option>
            </select>

            <div class="dua-kolom">
                <div>
                    <label for="tempat_lahir">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" id="tempat_lahir" value="<?php echo htmlspecialchars($input['tempat_lahir'] ?? ''); ?>">
                </div>
                <div>
                    <label for="tanggal_lahir">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="<?php echo htmlspecialchars($input['tanggal_lahir'] ?? ''); ?>">
                </div>
            </div>

            <label for="telpon">No. Telpon</label>
            <input type="text" name="telpon" id="telpon" value="<?php echo htmlspecialchars($input['telpon'] ?? ''); ?>">

            <label for="alamat">Alamat</label>
            <textarea name="alamat" id="alamat" rows="3"><?php echo htmlspecialchars($input['alamat'] ?? ''); ?></textarea>

            <label for="foto">Foto Profil (opsional)</label>
            <input type="file" name="foto" id="foto" accept="image/*">
            <div class="catatan">JPG/PNG/GIF/WEBP, maksimal 3MB.</div>

            <button type="submit">Daftar</button>
        </form>

        <a class="balik" href="login.php">Sudah punya akun? Login</a>
    <?php } ?>
</div>
</body>
</html>
