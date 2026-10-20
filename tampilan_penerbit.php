<?php include 'auth.php'; cek_staff(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Penerbit</title>
    <link rel="stylesheet" href="data.css">
</head>
<body>
<?php include "menu.php"; ?>

<h2 align="center">Data Penerbit</h2>

<?php
include "connection.php";

// Query mengambil data dari tabel penerbit
$sql = "SELECT * FROM penerbit";
$query = mysqli_query($conn, $sql);

$data = [];
while ($row = mysqli_fetch_assoc($query)) {
    $data[] = $row;
}
?>

<div align="center" style="margin-bottom: 15px;">
    <a href="form_penerbit.html">Tambah Data Penerbit</a>
</div>

<table border="1" align="center" cellpadding="8" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Kode Penerbit</th>
        <th>Nama Penerbit</th>
        <th>Aksi</th>
    </tr>

<?php foreach ($data as $penerbit) { ?>
    <tr>
        <td><?php echo $penerbit['penerbit_id']; ?></td>
        <td><?php echo $penerbit['kode_penerbit']; ?></td>
        <td><?php echo $penerbit['nama_penerbit']; ?></td>
        <td>
            <a href="edit_penerbit.php?id=<?php echo $penerbit['penerbit_id']; ?>">Edit</a> | 
            <a href="hapus_penerbit.php?id=<?php echo $penerbit['penerbit_id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
        </td>
    </tr>
<?php } ?>

</table>

<div class="footer" align="center" style="margin-top: 20px;">
    <p><b>Nama: Pashya Aladzany</b></p>
</div>

</body>
</html>
