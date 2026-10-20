<?php include 'auth.php'; cek_staff(); ?>
<?php

include "connection.php";

// Menangkap data dari form
$buku_id        = $_POST['buku_id'];
$anggota_id     = $_POST['anggota_id'];
$user_id        = $_POST['user_id'];
$jumlah         = $_POST['jumlah'];
$tanggal_pinjam = $_POST['tanggal_pinjam'];
$lama_pinjam    = $_POST['lama_pinjam'];
$keterangan     = $_POST['keterangan'];
$status         = $_POST['status'];

// Query INSERT (peminjaman_id AUTO_INCREMENT)
$sql = "INSERT INTO peminjaman 
        (buku_id, jumlah, tanggal_pinjam, lama_pinjam, keterangan, status, anggota_id, user_id) 
        VALUES 
        ('$buku_id', '$jumlah', '$tanggal_pinjam', '$lama_pinjam', '$keterangan', '$status', '$anggota_id', '$user_id')";

if (mysqli_query($conn, $sql)) {
    echo "<script>
            alert('Data Peminjaman Berhasil Ditambahkan');
            location='tampilan_peminjaman.php';
          </script>";
} else {
    echo "Gagal menambahkan data: " . mysqli_error($conn);
}

?>
