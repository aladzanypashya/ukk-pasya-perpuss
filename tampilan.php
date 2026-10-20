<?php include 'auth.php'; cek_login(); ?>

<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Buku</title>
    <link rel="stylesheet" href="data.css">
</head>
<body>
<?php include "menu.php"; ?>

<h2 align="center">Data Buku</h2>

<?php
include "connection.php";

// Query mengambil data dari tabel buku
$sql = "SELECT * FROM buku";
$query = mysqli_query($conn, $sql);

$data = [];
while ($row = mysqli_fetch_assoc($query)) {
    $data[] = $row;
}
?>

<?php if (is_staff()) { ?><div align="center" style="margin-bottom: 15px;">
    <a href="form.HTML">Tambah Data Buku</a>
</div><?php } ?>

<table border="1" align="center" cellpadding="8" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Kode Buku</th>
        <th>Judul</th>
        <th>Kategori ID</th>
        <th>Penerbit ID</th>
        <th>ISBN</th>
        <th>Pengarang</th>
        <th>Jumlah Halaman</th>
        <th>Jumlah Stok</th>
        <th>Tahun Terbit</th>
        <th>Sinopsis</th>
        <th>Gambar</th>
        <?php if (is_staff()) { ?><th>Aksi</th><?php } ?>
    </tr>

<?php foreach ($data as $buku) { ?>
    <tr>
        <td><?php echo $buku['buku_id']; ?></td>
        <td><?php echo $buku['kode_buku']; ?></td>
        <td><?php echo $buku['judul']; ?></td>
        <td><?php echo $buku['kategori_id']; ?></td>
        <td><?php echo $buku['penerbit_id']; ?></td>
        <td><?php echo $buku['isbn']; ?></td>
        <td><?php echo $buku['pengarang']; ?></td>
        <td><?php echo $buku['jumlah_halaman']; ?></td>
        <td><?php echo $buku['jumlah_stok']; ?></td>
        <td><?php echo $buku['tahun_terbit']; ?></td>
        <td><?php echo $buku['sinopsis']; ?></td>
        <td><?php echo $buku['gambar']; ?></td>
        <?php if (is_staff()) { ?><td>
            <a href="edit.php?id=<?php echo $buku['buku_id']; ?>">Edit</a> | 
            <a href="hapus.php?id=<?php echo $buku['buku_id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
        </td><?php } ?>
    </tr>
<?php } ?>

</table>

<div class="footer" align="center" style="margin-top: 20px;">
    
</div>

</body>
</html>
