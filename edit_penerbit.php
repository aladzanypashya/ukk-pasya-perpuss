<?php include 'auth.php'; cek_staff(); ?>
<?php

include "connection.php";

// Menangkap ID dari URL
$id = $_GET['id'];

// Query mengambil data berdasarkan penerbit_id
$data = $conn->query("SELECT * FROM penerbit WHERE penerbit_id='$id'");
$row = $data->fetch_assoc();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Data Penerbit</title>
</head>
<body>

<h2>Edit Data Penerbit</h2>

<form method="POST">

    <!-- Input hidden untuk menyimpan penerbit_id -->
    <input type="hidden" name="penerbit_id" value="<?php echo $row['penerbit_id']; ?>">

    KODE PENERBIT:
    <br>
    <input type="text" name="kode_penerbit" value="<?php echo $row['kode_penerbit']; ?>" required>
    <br><br>

    NAMA PENERBIT:
    <br>
    <input type="text" name="nama_penerbit" value="<?php echo $row['nama_penerbit']; ?>" required>
    <br><br>

    <input type="submit" name="update" value="Simpan Perubahan">

</form>

<br>
<a href="tampilan_penerbit.php">Kembali</a>

</body>
</html>

<?php

if (isset($_POST['update'])) {

    $id            = $_POST['penerbit_id'];
    $kode_penerbit = $_POST['kode_penerbit'];
    $nama_penerbit = $_POST['nama_penerbit'];

    // Query UPDATE
    $query = $conn->query("UPDATE penerbit SET
        kode_penerbit='$kode_penerbit',
        nama_penerbit='$nama_penerbit'
        WHERE penerbit_id='$id'
    ");

    if ($query) {
        echo "<script>
                alert('Data Berhasil Diubah');
                window.location.href = 'tampilan_penerbit.php';
              </script>";
    } else {
        echo "Data gagal diubah: " . $conn->error;
    }
}

?>
