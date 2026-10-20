<?php include 'auth.php'; cek_staff(); ?>
<?php

include "connection.php";

// Menangkap ID dari URL
$id = $_GET['id'];

// Query mengambil data berdasarkan buku_id
$data = $conn->query("SELECT * FROM buku WHERE buku_id='$id'");
$row = $data->fetch_assoc();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Data Buku</title>
</head>
<body>

<h2>Edit Data Buku</h2>

<form method="POST">

    <!-- Input hidden untuk menyimpan buku_id -->
    <input type="hidden" name="buku_id" value="<?php echo $row['buku_id']; ?>">

    KODE BUKU:
    <br>
    <input type="text" name="kode_buku" value="<?php echo $row['kode_buku']; ?>" required>
    <br><br>

    JUDUL:
    <br>
    <input type="text" name="judul" value="<?php echo $row['judul']; ?>" required>
    <br><br>

    KATEGORI ID:
    <br>
    <input type="number" name="kategori_id" value="<?php echo $row['kategori_id']; ?>" required>
    <br><br>

    PENERBIT ID:
    <br>
    <input type="number" name="penerbit_id" value="<?php echo $row['penerbit_id']; ?>" required>
    <br><br>

    ISBN:
    <br>
    <input type="text" name="isbn" value="<?php echo $row['isbn']; ?>">
    <br><br>
    
    PENGARANG:
    <br>
    <input type="text" name="pengarang" value="<?php echo $row['pengarang']; ?>">
    <br><br>
    
    JUMLAH HALAMAN:
    <br>
    <input type="number" name="jumlah_halaman" value="<?php echo $row['jumlah_halaman']; ?>">
    <br><br>
    
    JUMLAH STOK:
    <br>
    <input type="number" name="jumlah_stok" value="<?php echo $row['jumlah_stok']; ?>">
    <br><br>
    
    TAHUN TERBIT:
    <br>
    <input type="number" name="tahun_terbit" value="<?php echo $row['tahun_terbit']; ?>">
    <br><br>
    
    SINOPSIS:
    <br>
    <textarea name="sinopsis" rows="4" cols="30"><?php echo $row['sinopsis']; ?></textarea>
    <br><br>
    
    GAMBAR:
    <br>
    <input type="text" name="gambar" value="<?php echo $row['gambar']; ?>">
    <br><br>

    <input type="submit" name="update" value="Simpan Perubahan">

</form>

<br>
<a href="tampilan.php">Kembali</a>

</body>
</html>

<?php

if (isset($_POST['update'])) {

    $id             = $_POST['buku_id'];
    $buku           = $_POST['kode_buku'];
    $judul          = $_POST['judul'];
    $kategori       = $_POST['kategori_id'];
    $penerbit       = $_POST['penerbit_id'];
    $isbn           = $_POST['isbn'];
    $pengarang      = $_POST['pengarang'];
    $jumlah_halaman = $_POST['jumlah_halaman']; // Dipisah agar tidak menimpa nilai stok
    $jumlah_stok    = $_POST['jumlah_stok'];
    $tahun          = $_POST['tahun_terbit'];
    $sinopsis       = $_POST['sinopsis'];
    $gambar         = $_POST['gambar'];

    // Query UPDATE dengan koma di setiap antar-kolom dan klausa WHERE buku_id
    $query = $conn->query("UPDATE buku SET
        kode_buku='$buku',
        judul='$judul',
        kategori_id='$kategori',
        penerbit_id='$penerbit',
        isbn='$isbn',
        pengarang='$pengarang',
        jumlah_halaman='$jumlah_halaman',
        jumlah_stok='$jumlah_stok',
        tahun_terbit='$tahun',
        sinopsis='$sinopsis',
        gambar='$gambar'
        WHERE buku_id='$id'
    ");

    if ($query) {
        echo "<script>
                alert('Data Berhasil Diubah');
                window.location.href = 'tampilan.php';
              </script>";
    } else {
        echo "Data gagal diubah: " . $conn->error;
    }
}

?>

