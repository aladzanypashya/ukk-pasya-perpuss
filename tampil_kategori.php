<?php include 'auth.php'; cek_staff(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Kategori</title>
    <link rel="stylesheet" href="data.css">
</head>
<body>
<?php include "menu.php"; ?>

<h2 align="center">Data Kategori</h2>

<?php
include "connection.php";

// Query mengambil data dari tabel kategori
$sql = "SELECT * FROM kategori";
$query = mysqli_query($conn, $sql);

$data = [];
while ($row = mysqli_fetch_assoc($query)) {
    $data[] = $row;
}
?>

<div align="center" style="margin-bottom: 15px;">
    <a href="form_kategori.html">Tambah Data Kategori</a>
</div>

<table border="1" align="center" cellpadding="8" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Kode Kategori</th>
        <th>Nama Kategori</th>
        <th>Aksi</th>
    </tr>

<?php foreach ($data as $kategori) { ?>
    <tr>
        <td><?php echo $kategori['kategori_id']; ?></td>
        <td><?php echo $kategori['kode_kategori']; ?></td>
        <td><?php echo $kategori['nama_kategori']; ?></td>
        <td>
            <a href="edit_kategori.php?id=<?php echo $kategori['kategori_id']; ?>">Edit</a> | 
            <a href="hapus_kategori.php?id=<?php echo $kategori['kategori_id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
        </td>
    </tr>
<?php } ?>

</table>

<div class="footer" align="center" style="margin-top: 20px;">
    <p><b>Nama: Pashya Aladzany</b></p>
</div>

</body>
</html>
