<?php include 'auth.php'; cek_staff(); ?>
<?php

include "connection.php";

// Menangkap ID dari URL
$id = $_GET['id'];

// Query mengambil data berdasarkan pengembalian_id
$data = $conn->query("SELECT * FROM pengembalian WHERE pengembalian_id='$id'");
$row = $data->fetch_assoc();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Data Pengembalian</title>
</head>
<body>

<h2>Edit Data Pengembalian</h2>

<form method="POST">

    <!-- Hidden input ID pengembalian -->
    <input type="hidden" name="pengembalian_id" value="<?php echo $row['pengembalian_id']; ?>">

    <label>Pilih ID Peminjaman:</label><br>
    <select name="peminjaman_id" required>
        <?php
        $peminjaman = $conn->query("SELECT peminjaman_id, tanggal_pinjam FROM peminjaman");
        while ($p = $peminjaman->fetch_assoc()) {
            $selected = ($p['peminjaman_id'] == $row['peminjaman_id']) ? "selected" : "";
            echo "<option value='".$p['peminjaman_id']."' $selected>ID: ".$p['peminjaman_id']." (Tgl Pinjam: ".$p['tanggal_pinjam'].")</option>";
        }
        ?>
    </select><br><br>

    <label>Tanggal Kembali:</label><br>
    <input type="date" name="tanggal_kembali" value="<?php echo $row['tanggal_kembali']; ?>" required><br><br>

    <label>Pilih Petugas (User):</label><br>
    <select name="user_id" required>
        <?php
        $user = $conn->query("SELECT user_id, nama FROM user");
        while ($u = $user->fetch_assoc()) {
            $selected = ($u['user_id'] == $row['user_id']) ? "selected" : "";
            echo "<option value='".$u['user_id']."' $selected>".$u['user_id']." - ".$u['nama']."</option>";
        }
        ?>
    </select><br><br>

    <input type="submit" name="update" value="Simpan Perubahan">

</form>

<br>
<a href="tampilan_pengembalian.php">Kembali</a>

</body>
</html>

<?php

if (isset($_POST['update'])) {

    $pengembalian_id = $_POST['pengembalian_id'];
    $peminjaman_id   = $_POST['peminjaman_id'];
    $tanggal_kembali = $_POST['tanggal_kembali'];
    $user_id         = $_POST['user_id'];

    // Query UPDATE
    $query = $conn->query("UPDATE pengembalian SET
        peminjaman_id='$peminjaman_id',
        tanggal_kembali='$tanggal_kembali',
        user_id='$user_id'
        WHERE pengembalian_id='$pengembalian_id'
    ");

    if ($query) {
        echo "<script>
                alert('Data Pengembalian Berhasil Diubah');
                window.location.href = 'tampilan_pengembalian.php';
              </script>";
    } else {
        echo "Data gagal diubah: " . $conn->error;
    }
}

?>
