<?php include 'auth.php'; cek_staff(); ?>
<?php

include "connection.php";

// Menangkap ID dari URL
$id = $_GET['id'];

// Query mengambil data berdasarkan user_id
$data = $conn->query("SELECT * FROM user WHERE user_id='$id'");
$row = $data->fetch_assoc();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Data User</title>
</head>
<body>

<h2>Edit Data User</h2>

<form method="POST">

    <!-- Input hidden untuk menyimpan user_id -->
    <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">

    NAMA LENGKAP:
    <br>
    <input type="text" name="nama" value="<?php echo $row['nama']; ?>" required>
    <br><br>

    USERNAME:
    <br>
    <input type="text" name="username" value="<?php echo $row['username']; ?>" required>
    <br><br>

    PASSWORD:
    <br>
    <input type="text" name="password" value="<?php echo $row['password']; ?>" required>
    <br><br>

    LEVEL:
    <br>
    <select name="level" required>
        <option value="admin" <?php if($row['level'] == 'admin') echo 'selected'; ?>>Admin</option>
        <option value="petugas" <?php if($row['level'] == 'petugas') echo 'selected'; ?>>Petugas</option>
    </select>
    <br><br>

    <input type="submit" name="update" value="Simpan Perubahan">

</form>

<br>
<a href="tampilan_user.php">Kembali</a>

</body>
</html>

<?php

if (isset($_POST['update'])) {

    $id       = $_POST['user_id'];
    $nama     = $_POST['nama'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $level    = $_POST['level'];

    // Query UPDATE
    $query = $conn->query("UPDATE user SET
        nama='$nama',
        username='$username',
        password='$password',
        level='$level'
        WHERE user_id='$id'
    ");

    if ($query) {
        echo "<script>
                alert('Data Berhasil Diubah');
                window.location.href = 'tampilan_user.php';
              </script>";
    } else {
        echo "Data gagal diubah: " . $conn->error;
    }
}

?>
