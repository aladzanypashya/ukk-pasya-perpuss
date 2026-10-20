<?php include 'auth.php'; cek_staff(); ?>
<?php

include "connection.php";

// Menangkap ID dari URL (anggota_id)
$id = $_GET['id'];

// Query mengambil data berdasarkan anggota_id
$data = $conn->query("SELECT * FROM anggota WHERE anggota_id='$id'");
$row = $data->fetch_assoc();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Data Anggota</title>
</head>
<body>

<h2>Edit Data Anggota</h2>

<form method="POST">

    <!-- Input hidden untuk menyimpan anggota_id -->
    <input type="hidden" name="anggota_id" value="<?php echo $row['anggota_id']; ?>">

    KODE ANGGOTA:
    <br>
    <input type="text" name="kode_anggota" value="<?php echo $row['kode_anggota']; ?>" required>
    <br><br>

    NAMA:
    <br>
    <input type="text" name="nama" value="<?php echo $row['nama']; ?>" required>
    <br><br>

  PASSWORD:
    <br>
    <input type="text" name="password" value="<?php echo $row['nama']; ?>" required>
    <br><br>
  

    JENIS KELAMIN:
    <br>
    <select name="jenis_kelamin" required>
        <option value="pria" <?php if($row['jenis_kelamin'] == 'pria') echo 'selected'; ?>>Pria</option>
        <option value="wanita" <?php if($row['jenis_kelamin'] == 'wanita') echo 'selected'; ?>>Wanita</option>
    </select>
    <br><br>

    TEMPAT LAHIR:
    <br>
    <input type="text" name="tempat_lahir" value="<?php echo $row['tempat_lahir']; ?>">
    <br><br>

    TANGGAL LAHIR:
    <br>
    <input type="date" name="tanggal_lahir" value="<?php echo $row['tanggal_lahir']; ?>">
    <br><br>
    
    TELPON:
    <br>
    <input type="text" name="telpon" value="<?php echo $row['telpon']; ?>">
    <br><br>
    
    ALAMAT:
    <br>
    <textarea name="alamat" rows="4" cols="30"><?php echo $row['alamat']; ?></textarea>
    <br><br>
    
    FOTO:
    <br>
    <input type="text" name="foto" value="<?php echo $row['foto']; ?>">
    <br><br>

    <input type="submit" name="update" value="Simpan Perubahan">

</form>

<br>
<a href="tampilan_anggota.php">Kembali</a>

</body>
</html>

<?php

if (isset($_POST['update'])) {

    $id             = $_POST['anggota_id'];
    $kode_anggota   = $_POST['kode_anggota'];
    $nama           = $_POST['nama'];
    $jenis_kelamin  = $_POST['jenis_kelamin'];
    $tempat_lahir   = $_POST['tempat_lahir'];
    $tanggal_lahir  = $_POST['tanggal_lahir'];
    $telpon         = $_POST['telpon'];
    $alamat         = $_POST['alamat'];
    $foto           = $_POST['foto'];

    // Query UPDATE data anggota berdasarkan anggota_id
    $query = $conn->query("UPDATE anggota SET
        kode_anggota='$kode_anggota',
        nama='$nama',
        jenis_kelamin='$jenis_kelamin',
        tempat_lahir='$tempat_lahir',
        tanggal_lahir='$tanggal_lahir',
        telpon='$telpon',
        alamat='$alamat',
        foto='$foto'
        WHERE anggota_id='$id'
    ");

    if ($query) {
        echo "<script>
                alert('Data Anggota Berhasil Diubah');
                window.location.href = 'tampilan.php';
              </script>";
    } else {
        echo "Data gagal diubah: " . $conn->error;
    }
}

?>
