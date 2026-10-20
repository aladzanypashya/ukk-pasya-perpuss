<?php include 'auth.php'; cek_staff(); ?>
<?php

include "connection.php";

// Menangkap data dari form
$kode_kategori = $_POST['kode_kategori'];
$nama_kategori = $_POST['nama_kategori'];

// Query INSERT (kategori_id AUTO_INCREMENT)
$sql = "INSERT INTO kategori (kode_kategori, nama_kategori) VALUES ('$kode_kategori', '$nama_kategori')";

if (mysqli_query($conn, $sql)) {
    echo "<script>
            alert('Data Berhasil Ditambahkan');
            location='tampilan_kategori.php';
          </script>";
} else {
    echo "Gagal menambahkan data: " . mysqli_error($conn);
}

?>
