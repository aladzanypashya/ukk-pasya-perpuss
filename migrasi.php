<?php
// migrasi.php - menyiapkan tabel peminjaman untuk fitur pengajuan. Cukup dibuka sekali di browser
// (harus login sebagai admin/petugas). Aman dijalankan berulang kali.
include 'auth.php';
cek_staff();
include "connection.php";
mysqli_report(MYSQLI_REPORT_OFF);

include 'migrasi_lib.php';

$hasil = jalankan_migrasi($conn);

$semua_ok = true;
foreach ($hasil as $h) { if (!$h[0]) { $semua_ok = false; } }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Migrasi Database</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; }
        .box { background: #fff; max-width: 640px; margin: 30px auto; padding: 24px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,.12); }
        h2 { color: navy; margin-top: 0; }
        .baris { padding: 10px; border-radius: 4px; margin-bottom: 8px; font-size: 14px; word-break: break-word; }
        .ok { background: #d1e7dd; color: #0f5132; }
        .gagal { background: #f8d7da; color: #842029; }
        a.btn { display: inline-block; margin-top: 12px; padding: 9px 16px; background: #007BFF; color: #fff; border-radius: 4px; text-decoration: none; }
    </style>
</head>
<body>
<div class="box">
    <h2>Migrasi Tabel Peminjaman</h2>
    <?php foreach ($hasil as $h) { ?>
        <div class="baris <?php echo $h[0] ? 'ok' : 'gagal'; ?>">
            <?php echo $h[0] ? '&#10004; ' : '&#10008; '; echo htmlspecialchars($h[1]); ?>
        </div>
    <?php } ?>

    <?php if ($semua_ok) { ?>
        <p>Selesai. Fitur pengajuan peminjaman sudah siap dipakai. File <b>migrasi.php</b> ini boleh dihapus.</p>
        <a class="btn" href="dashboard.php">Ke Dashboard</a>
    <?php } else { ?>
        <p>Ada langkah yang gagal. Salin pesan merah di atas dan kirim ke saya supaya bisa diperbaiki.</p>
    <?php } ?>
</div>
</body>
</html>
