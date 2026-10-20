<?php include 'auth.php'; cek_staff(); ?>
<?php

include "connection.php";

// Menangkap ID dari URL
$id = $_GET['id'];

// Query mengambil data berdasarkan peminjaman_id
$data = $conn->query("SELECT * FROM peminjaman WHERE peminjaman_id='$id'");
$row = $data->fetch_assoc();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Data Peminjaman</title>
</head>
<body>

<h2>Edit Data Peminjaman</h2>

<form method="POST">

    <!-- Input hidden untuk menyimpan peminjaman_id -->
    <input type="hidden" name="peminjaman_id" value="<?php echo $row['peminjaman_id']; ?>">

    BUKU ID:
    <br>
    <input type="number" name="buku_id" value="<?php echo $row['buku_id']; ?>" required>
    <br><br>

    ANGGOTA ID:
    <br>
    <input type="number" name="anggota_id" value="<?php echo $row['anggota_id']; ?>" required>
    <br><br>

    USER ID:
    <br>
    <input type="number" name="user_id" value="<?php echo $row['user_id']; ?>" required>
    <br><br>

    JUMLAH:
    <br>
    <input type="number" name="jumlah" value="<?php echo $row['jumlah']; ?>" required>
    <br><br>

    TANGGAL PINJAM:
    <br>
    <input type="date" name="tanggal_pinjam" value="<?php echo $row['tanggal_pinjam']; ?>" required>
    <br><br>

    LAMA PINJAM (HARI):
    <br>
    <input type="number" name="lama_pinjam" value="<?php echo $row['lama_pinjam']; ?>" required>
    <br><br>

    KETERANGAN:
    <br>
    <textarea name="keterangan" rows="3" cols="30"><?php echo $row['keterangan']; ?></textarea>
    <br><br>

    STATUS:
    <br>
    <select name="status" required>
        <option value="diproses" <?php if($row['status'] == 'diproses') echo 'selected'; ?>>Sedang Diproses</option>
        <option value="dipinjam" <?php if($row['status'] == 'dipinjam') echo 'selected'; ?>>Dipinjam</option>
        <option value="ditolak" <?php if($row['status'] == 'ditolak') echo 'selected'; ?>>Ditolak</option>
        <option value="sudah dikembalikan" <?php if($row['status'] == 'sudah dikembalikan') echo 'selected'; ?>>Sudah Dikembalikan</option>
    </select>
    <br><br>

    <input type="submit" name="update" value="Simpan Perubahan">

</form>

<br>
<a href="tampilan_peminjaman.php">Kembali</a>

</body>
</html>

<?php

if (isset($_POST['update'])) {

    $id             = $_POST['peminjaman_id'];
    $buku_id        = $_POST['buku_id'];
    $anggota_id     = $_POST['anggota_id'];
    $user_id        = $_POST['user_id'];
    $jumlah         = $_POST['jumlah'];
    $tanggal_pinjam = $_POST['tanggal_pinjam'];
    $lama_pinjam    = $_POST['lama_pinjam'];
    $keterangan     = $_POST['keterangan'];
    $status         = $_POST['status'];

    // Query UPDATE
    $query = $conn->query("UPDATE peminjaman SET
        buku_id='$buku_id',
        jumlah='$jumlah',
        tanggal_pinjam='$tanggal_pinjam',
        lama_pinjam='$lama_pinjam',
        keterangan='$keterangan',
        status='$status',
        anggota_id='$anggota_id',
        user_id='$user_id'
        WHERE peminjaman_id='$id'
    ");

    if ($query) {
        echo "<script>
                alert('Data Berhasil Diubah');
                window.location.href = 'tampilan_peminjaman.php';
              </script>";
    } else {
        echo "Data gagal diubah: " . $conn->error;
    }
}

?>
