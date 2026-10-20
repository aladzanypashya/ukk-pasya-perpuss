<?php
include 'auth.php';
cek_staff();
include "connection.php";
mysqli_report(MYSQLI_REPORT_OFF);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: tampilan_peminjaman.php");
    exit;
}

$id   = (int)($_POST['id'] ?? 0);
$aksi = $_POST['aksi'] ?? '';

if ($aksi === 'acc') {
    $status_baru = 'dipinjam';
} elseif ($aksi === 'tolak') {
    $status_baru = 'ditolak';
} else {
    header("Location: tampilan_peminjaman.php");
    exit;
}

$petugas_id = (int)$_SESSION['id']; // user_id admin/petugas yang memproses

// Hanya pengajuan yang masih 'diproses' yang bisa di-ACC / ditolak
$stmt = mysqli_prepare($conn, "UPDATE peminjaman SET status = ?, user_id = ? WHERE peminjaman_id = ? AND status = 'diproses'");
mysqli_stmt_bind_param($stmt, "sii", $status_baru, $petugas_id, $id);
mysqli_stmt_execute($stmt);

$pesan = (mysqli_stmt_affected_rows($stmt) > 0) ? $aksi : 'gagal';
header("Location: tampilan_peminjaman.php?pesan=" . $pesan);
exit;
?>
