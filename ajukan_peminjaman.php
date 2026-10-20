<?php
include 'auth.php';
cek_login();

// Admin/petugas memakai form input langsung
if (is_staff()) {
    header("Location: form_peminjaman.html");
    exit;
}

include "connection.php";
mysqli_report(MYSQLI_REPORT_OFF);

$error = "";

// Daftar buku untuk pilihan
$daftar_buku = [];
$q = mysqli_query($conn, "SELECT buku_id, judul, jumlah_stok FROM buku ORDER BY judul");
while ($q && $r = mysqli_fetch_assoc($q)) {
    $daftar_buku[] = $r;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $buku_id    = (int)($_POST['buku_id'] ?? 0);
    $jumlah     = (int)($_POST['jumlah'] ?? 0);
    $tanggal    = $_POST['tanggal_pinjam'] ?? '';
    $lama       = (int)($_POST['lama_pinjam'] ?? 0);
    $keterangan = trim($_POST['keterangan'] ?? '');
    $anggota_id = (int)$_SESSION['id'];

    $tgl_ok = DateTime::createFromFormat('Y-m-d', $tanggal);

    if ($buku_id <= 0 || $jumlah < 1 || $lama < 1 || !$tgl_ok || $tgl_ok->format('Y-m-d') !== $tanggal) {
        $error = "Data belum lengkap atau tidak valid.";
    } else {
        // Pastikan buku ada dan stok cukup
        $s = mysqli_prepare($conn, "SELECT jumlah_stok FROM buku WHERE buku_id = ?");
        mysqli_stmt_bind_param($s, "i", $buku_id);
        mysqli_stmt_execute($s);
        $b = mysqli_fetch_assoc(mysqli_stmt_get_result($s));

        if (!$b) {
            $error = "Buku tidak ditemukan.";
        } elseif ($jumlah > (int)$b['jumlah_stok']) {
            $error = "Jumlah melebihi stok buku (stok: " . (int)$b['jumlah_stok'] . ").";
        } else {
            // Status awal 'diproses'; user_id (petugas) kosong sampai di-ACC
            $sql_insert = "INSERT INTO peminjaman (buku_id, jumlah, tanggal_pinjam, lama_pinjam, keterangan, status, anggota_id, user_id)
                           VALUES (?, ?, ?, ?, ?, 'diproses', ?, NULL)";

            for ($percobaan = 1; $percobaan <= 2; $percobaan++) {
                $stmt = mysqli_prepare($conn, $sql_insert);
                mysqli_stmt_bind_param($stmt, "iisisi", $buku_id, $jumlah, $tanggal, $lama, $keterangan, $anggota_id);

                if (mysqli_stmt_execute($stmt)) {
                    header("Location: tampilan_peminjaman.php?pesan=diajukan");
                    exit;
                }

                $errno = mysqli_stmt_errno($stmt);
                $error = "Gagal mengajukan: " . mysqli_stmt_error($stmt);

                // Struktur tabel belum siap (status masih ENUM / user_id belum boleh kosong):
                // perbaiki otomatis lalu coba sekali lagi
                if ($percobaan === 1 && in_array($errno, [1265, 1048, 1364, 1366])) {
                    include_once 'migrasi_lib.php';
                    $hasil_mig = jalankan_migrasi($conn);
                    continue;
                }
                break;
            }

            if (isset($hasil_mig)) {
                foreach ($hasil_mig as $m) {
                    if (!$m[0]) {
                        $error .= " | Perbaikan database gagal: " . $m[1];
                    }
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
    <title>Ajukan Peminjaman</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; }
        .box { background: #fff; max-width: 420px; margin: 24px auto; padding: 24px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,.12); }
        h2 { color: navy; margin-top: 0; }
        label { display: block; margin: 12px 0 4px; font-size: 14px; font-weight: bold; }
        input, select, textarea { width: 100%; padding: 8px; border: 1px solid #bbb; border-radius: 4px; box-sizing: border-box; font-size: 14px; }
        .aksi { margin-top: 20px; }
        button, .kembali { padding: 9px 16px; border: 0; border-radius: 4px; font-size: 14px; cursor: pointer; text-decoration: none; display: inline-block; }
        button { background: #007BFF; color: #fff; }
        .kembali { background: #6c757d; color: #fff; }
        .error { background: #f8d7da; color: #842029; padding: 9px; border-radius: 4px; font-size: 14px; }
        .info { background: #fff3cd; color: #664d03; padding: 9px; border-radius: 4px; font-size: 13px; margin-bottom: 8px; }
    </style>
</head>
<body>

<?php include "menu.php"; ?>

<div class="box">
    <h2>Ajukan Peminjaman</h2>
    <div class="info">Pengajuan akan berstatus <b>Sedang Diproses</b> sampai disetujui admin/petugas.</div>

    <?php if ($error) { ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php } ?>

    <form method="post">
        <label for="buku_id">Buku</label>
        <select name="buku_id" id="buku_id" required>
            <option value="">-- Pilih Buku --</option>
            <?php foreach ($daftar_buku as $b) { ?>
                <option value="<?php echo (int)$b['buku_id']; ?>"
                    <?php if ((int)($_POST['buku_id'] ?? 0) === (int)$b['buku_id']) echo 'selected'; ?>
                    <?php if ((int)$b['jumlah_stok'] < 1) echo 'disabled'; ?>>
                    <?php echo htmlspecialchars($b['judul']); ?> (stok: <?php echo (int)$b['jumlah_stok']; ?>)
                </option>
            <?php } ?>
        </select>

        <label for="jumlah">Jumlah Buku</label>
        <input type="number" name="jumlah" id="jumlah" min="1" value="<?php echo (int)($_POST['jumlah'] ?? 1); ?>" required>

        <label for="tanggal_pinjam">Tanggal Pinjam</label>
        <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" value="<?php echo htmlspecialchars($_POST['tanggal_pinjam'] ?? date('Y-m-d')); ?>" required>

        <label for="lama_pinjam">Lama Pinjam (Hari)</label>
        <input type="number" name="lama_pinjam" id="lama_pinjam" min="1" placeholder="Misal: 7" value="<?php echo htmlspecialchars($_POST['lama_pinjam'] ?? ''); ?>" required>

        <label for="keterangan">Keterangan (opsional)</label>
        <textarea name="keterangan" id="keterangan" rows="3"><?php echo htmlspecialchars($_POST['keterangan'] ?? ''); ?></textarea>

        <div class="aksi">
            <button type="submit">Ajukan</button>
            <a class="kembali" href="tampilan_peminjaman.php">Kembali</a>
        </div>
    </form>
</div>

</body>
</html>
