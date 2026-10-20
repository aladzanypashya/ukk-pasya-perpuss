<?php include 'auth.php'; cek_staff(); ?>
<?php

include "connection.php";

// Menangkap ID dari URL
$id = $_GET['id'];

// Query mengambil data berdasarkan kategori_id
$data = $conn->query("SELECT * FROM kategori WHERE kategori_id='$id'");
$row = $data->fetch_assoc();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Data Kategori</title>
</head>
<body>

<h2>Edit Data Kategori</h2>

<form method="POST">

    <!-- Input hidden untuk menyimpan kategori_id -->
    <input type="hidden" name="kategori_id" value="<?php echo $row['kategori_id']; ?>">

    KODE KATEGORI:
    <br>
    <input type="text" name="kode_kategori" value="<?php echo $row['kode_kategori']; ?>" required>
    <br><br>

    NAMA KATEGORI:
    <br>
    <input type="text" name="nama_kategori" value="<?php echo $row['nama_kategori']; ?>" required>
    <br><br>

    <input type="submit" name="update" value="Simpan Perubahan">

</form>

<br>
<a href="tampil_kategori.php">Kembali</a>

</body>
</html>

<?php

if (isset($_POST['update'])) {

    $id            = $_POST['kategori_id'];
    $kode_kategori = $_POST['kode_kategori'];
    $nama_kategori = $_POST['nama_kategori'];

    // Query UPDATE
    $query = $conn->query("UPDATE kategori SET
        kode_kategori='$kode_kategori',
        nama_kategori='$nama_kategori'
        WHERE kategori_id='$id'
    ");

    if ($query) {
        echo "<script>
                alert('Data Berhasil Diubah');
                window.location.href = 'tampilan_kategori.php';
              </script>";
    } else {
        echo "Data gagal diubah: " . $conn->error;
    }
}

?>
