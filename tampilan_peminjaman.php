<?php include 'auth.php'; cek_login(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Peminjaman</title>
    <link rel="stylesheet" href="data.css">
    <style>
        .badge { display: inline-block; padding: 3px 10px; border-radius: 12px; font-size: 13px; font-weight: bold; }
        .b-diproses { background: #fff3cd; color: #664d03; }
        .b-dipinjam { background: #cfe2ff; color: #084298; }
        .b-kembali  { background: #d1e7dd; color: #0f5132; }
        .b-ditolak  { background: #f8d7da; color: #842029; }
        .pesan { width: 80%; margin: 10px auto; padding: 10px; background: #d1e7dd; color: #0f5132; border-radius: 4px; text-align: center; }
        .btn-acc, .btn-tolak { border: 0; color: #fff; padding: 4px 10px; border-radius: 4px; cursor: pointer; }
        .btn-acc { background: #198754; }
        .btn-tolak { background: #dc3545; }
        form.inline { display: inline; margin: 0; }
    </style>
</head>
<body>

<?php include "menu.php"; ?>

<h2 align="center">Data Peminjaman Buku</h2>

<?php
include "connection.php";

function h($s) { return htmlspecialchars((string)$s); }

$daftar_pesan = [
    'diajukan' => 'Pengajuan berhasil dikirim. Status: sedang diproses, menunggu persetujuan admin/petugas.',
    'acc'      => 'Peminjaman disetujui.',
    'tolak'    => 'Peminjaman ditolak.',
    'gagal'    => 'Pengajuan ini sudah diproses sebelumnya.',
];
$pesan = $_GET['pesan'] ?? '';

$badge = [
    'diproses'           => ['Sedang Diproses',    'b-diproses'],
    'dipinjam'           => ['Dipinjam',           'b-dipinjam'],
    'sudah dikembalikan' => ['Sudah Dikembalikan', 'b-kembali'],
    'ditolak'            => ['Ditolak',            'b-ditolak'],
];

// Pengajuan yang masih diproses ditampilkan paling atas
$sql = "SELECT * FROM peminjaman ORDER BY (status = 'diproses') DESC, peminjaman_id DESC";
$query = mysqli_query($conn, $sql);

$data = [];
if ($query) {
    while ($row = mysqli_fetch_assoc($query)) {
        $data[] = $row;
    }
}
?>

<?php if (isset($daftar_pesan[$pesan])) { ?>
    <div class="pesan"><?php echo h($daftar_pesan[$pesan]); ?></div>
<?php } ?>

<div align="center" style="margin-bottom: 15px;">
    <?php if (is_staff()) { ?>
        <a href="form_peminjaman.html">Tambah Data Peminjaman</a>
    <?php } else { ?>
        <a href="ajukan_peminjaman.php">Ajukan Peminjaman</a>
    <?php } ?>
</div>

<table border="1" align="center" cellpadding="8" cellspacing="0">
    <tr>
        <th>ID Peminjaman</th>
        <th>Buku ID</th>
        <th>Jumlah</th>
        <th>Tanggal Pinjam</th>
        <th>Lama Pinjam (Hari)</th>
        <th>Keterangan</th>
        <th>Status</th>
        <th>Anggota ID</th>
        <th>User ID</th>
        <?php if (is_staff()) { ?><th>Aksi</th><?php } ?>
    </tr>

<?php if (empty($data)) { ?>
    <tr>
        <td colspan="<?php echo is_staff() ? 10 : 9; ?>" align="center">Belum ada data peminjaman.</td>
    </tr>
<?php } ?>

<?php foreach ($data as $pinjam) {
    $st = $pinjam['status'];
    $b  = $badge[$st] ?? [$st, ''];
?>
    <tr>
        <td><?php echo h($pinjam['peminjaman_id']); ?></td>
        <td><?php echo h($pinjam['buku_id']); ?></td>
        <td><?php echo h($pinjam['jumlah']); ?></td>
        <td><?php echo h($pinjam['tanggal_pinjam']); ?></td>
        <td><?php echo h($pinjam['lama_pinjam']); ?> hari</td>
        <td><?php echo h($pinjam['keterangan']); ?></td>
        <td><span class="badge <?php echo $b[1]; ?>"><?php echo h($b[0]); ?></span></td>
        <td><?php echo h($pinjam['anggota_id']); ?></td>
        <td><?php echo $pinjam['user_id'] ? h($pinjam['user_id']) : '-'; ?></td>
        <?php if (is_staff()) { ?>
        <td>
            <?php if ($st === 'diproses') { ?>
                <form class="inline" method="post" action="proses_peminjaman.php" onsubmit="return confirm('Setujui peminjaman ini?')">
                    <input type="hidden" name="id" value="<?php echo (int)$pinjam['peminjaman_id']; ?>">
                    <input type="hidden" name="aksi" value="acc">
                    <button type="submit" class="btn-acc">ACC</button>
                </form>
                <form class="inline" method="post" action="proses_peminjaman.php" onsubmit="return confirm('Tolak peminjaman ini?')">
                    <input type="hidden" name="id" value="<?php echo (int)$pinjam['peminjaman_id']; ?>">
                    <input type="hidden" name="aksi" value="tolak">
                    <button type="submit" class="btn-tolak">Tolak</button>
                </form>
                <br><br>
            <?php } ?>
            <a href="edit_peminjaman.php?id=<?php echo (int)$pinjam['peminjaman_id']; ?>">Edit</a> |
            <a href="hapus_peminjaman.php?id=<?php echo (int)$pinjam['peminjaman_id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
        </td>
        <?php } ?>
    </tr>
<?php } ?>

</table>

<div class="footer" align="center" style="margin-top: 20px;">
    <p><b>Nama: Pashya Aladzany</b></p>
</div>

</body>
</html>
