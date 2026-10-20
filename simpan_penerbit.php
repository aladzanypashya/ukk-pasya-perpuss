<?php include 'auth.php'; cek_staff(); ?>
<?php

include "connection.php";

// Menangkap data dari form
$kode_penerbit = $_POST['kode_penerbit'];
$nama_penerbit = $_POST['nama_penerbit'];

// Query INSERT (penerbit_id AUTO_INCREMENT)
$sql = "INSERT INTO penerbit (kode_penerbit, nama_penerbit) VALUES ('$kode_penerbit', '$nama_penerbit')";

if (mysqli_query($conn, $sql)) {
    echo "<script>
            alert('Data Berhasil Ditambahkan');
            location='tampilan_penerbit.php';
          </script>";
} else {
    echo "Gagal menambahkan data: " . mysqli_error($conn);
}

?>
