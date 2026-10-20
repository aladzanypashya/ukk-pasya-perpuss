<?php include 'auth.php'; cek_login(); ?>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Pengembalian</title>
    <link rel="stylesheet" href="data.css">
</head>
<body>
<?php include "menu.php"; ?>

<h2 align="center">Data Pengembalian</h2>

<?php
include "connection.php";

// Query mengambil data dari tabel pengembalian
$sql = "SELECT * FROM pengembalian";
$query = mysqli_query($conn, $sql);

$data = [];
if ($query && mysqli_num_rows($query) > 0) {
    while ($row = mysqli_fetch_assoc($query)) {
        $data[] = $row;
    }
}
?>

<?php if (is_staff()) { ?><div align="center" style="margin-bottom: 15px;">
    <a href="form_pengembalian.html">Tambah Data Pengembalian</a>
</div><?php } ?>

<table border="1" align="center" cellpadding="8" cellspacing="0">
    <tr>
        <th>ID Pengembalian</th>
        <th>ID Peminjaman</th>
        <th>Tanggal Kembali</th>
        <th>ID User</th>
        <?php if (is_staff()) { ?><th>Aksi</th><?php } ?>
    </tr>

<?php if (!empty($data)) { ?>
    <?php foreach ($data as $pengembalian) { ?>
        <tr>
            <td><?php echo $pengembalian['pengembalian_id']; ?></td>
            <td><?php echo $pengembalian['peminjaman_id']; ?></td>
            <td><?php echo $pengembalian['tanggal_kembali']; ?></td>
            <td><?php echo $pengembalian['user_id']; ?></td>
            <?php if (is_staff()) { ?><td>
                <a href="edit_pengembalian.php?id=<?php echo $pengembalian['pengembalian_id']; ?>">Edit</a> | 
                <a href="hapus_pengembalian.php?id=<?php echo $pengembalian['pengembalian_id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
            </td><?php } ?>
        </tr>
    <?php } ?>
<?php } else { ?>
    <tr>
        <td colspan="<?php echo is_staff() ? 5 : 4; ?>" align="center">Belum ada data pengembalian.</td>
    </tr>
<?php } ?>

</table>

<div class="footer" align="center" style="margin-top: 20px;">
    <b>Nama: Pashya Aladzany</b>
</div>

</body>
</html>
