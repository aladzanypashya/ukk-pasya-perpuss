<?php include 'auth.php'; cek_staff(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Anggota</title>
    <link rel="stylesheet" href="data.css">
</head>
<body>
<?php include "menu.php"; ?>

<h2 align="center">Data Anggota</h2>

<?php
include "connection.php";

// Query mengambil data dari tabel anggota
$sql = "SELECT * FROM anggota";
$query = mysqli_query($conn, $sql);

$data = [];
while ($row = mysqli_fetch_assoc($query)) {
    $data[] = $row;
}
?>

<div align="center" style="margin-bottom: 15px;">
    <a href="form_anggota.html">Tambah Data Anggota</a>
</div>

<table border="1" align="center" cellpadding="8" cellspacing="0">
    <tr bgcolor="#007bff" style="color: white;">
        <th>ID</th>
        <th>Kode Anggota</th>
        <th>Nama</th>
        <th>Password</th>
        <th>Jenis Kelamin</th>
        <th>Tempat Lahir</th>
        <th>Tanggal Lahir</th>
        <th>Telpon</th>
        <th>Alamat</th>
        <th>Foto</th>
        <th>Aksi</th>
    </tr>

<?php foreach ($data as $anggota) { ?>
    <tr>
        <td><?php echo $anggota['anggota_id']; ?></td>
        <td><?php echo $anggota['kode_anggota']; ?></td>
        <td><?php echo $anggota['nama']; ?></td>
        <td><?php echo isset($anggota['password']) ? $anggota['password'] : '-'; ?></td>
        <td><?php echo $anggota['jenis_kelamin']; ?></td>
        <td><?php echo $anggota['tempat_lahir']; ?></td>
        <td><?php echo $anggota['tanggal_lahir']; ?></td>
        <td><?php echo isset($anggota['telpon']) ? $anggota['telpon'] : (isset($anggota['no_telp']) ? $anggota['no_telp'] : '-'); ?></td>
        <td><?php echo $anggota['alamat']; ?></td>
        <td><?php echo $anggota['foto']; ?></td>
        <td>
            <a href="edit_anggota.php?id=<?php echo $anggota['id']; ?>">Edit</a> | 
            <a href="hapus_anggota.php?id=<?php echo $anggota['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
        </td>
    </tr>
<?php } ?>

</table>

<div class="footer" align="center" style="margin-top: 20px;">
</div>

</body>
</html>
