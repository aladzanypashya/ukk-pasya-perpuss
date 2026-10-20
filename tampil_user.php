<?php include 'auth.php'; cek_staff(); ?>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data User</title>
    <link rel="stylesheet" href="data.css">
</head>
<body>
<?php include "menu.php"; ?>

<h2 align="center">Data User</h2>

<?php
include "connection.php";

// Query mengambil data dari tabel user
$sql = "SELECT * FROM user";
$query = mysqli_query($conn, $sql);

$data = [];
if ($query && mysqli_num_rows($query) > 0) {
    while ($row = mysqli_fetch_assoc($query)) {
        $data[] = $row;
    }
}
?>

<div align="center" style="margin-bottom: 15px;">
    <a href="form_user.html">Tambah Data User</a>
</div>

<table border="1" align="center" cellpadding="8" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Nama</th>
        <th>Username</th>
        <th>Password</th>
        <th>Level</th>
        <th>Aksi</th>
    </tr>

<?php if (!empty($data)) { ?>
    <?php foreach ($data as $user) { ?>
        <tr>
            <td><?php echo $user['user_id']; ?></td>
            <td><?php echo $user['nama']; ?></td>
            <td><?php echo $user['username']; ?></td>
            <td><?php echo $user['password']; ?></td>
            <td><?php echo $user['level']; ?></td>
            <td>
                <a href="edit_user.php?id=<?php echo $user['user_id']; ?>">Edit</a> | 
                <a href="hapus_user.php?id=<?php echo $user['user_id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
            </td>
        </tr>
    <?php } ?>
<?php } else { ?>
    <tr>
        <td colspan="6" align="center">Belum ada data user.</td>
    </tr>
<?php } ?>

</table>

<div class="footer" align="center" style="margin-top: 20px;">
    <b>Nama: Pashya Aladzany</b>
</div>

</body>
</html>
