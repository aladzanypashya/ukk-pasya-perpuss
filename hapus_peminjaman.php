<?php include 'auth.php'; cek_staff(); ?>
<?php

include "connection.php";

// Menangkap ID dari URL
$id = $_GET['id'];

// Query DELETE berdasarkan peminjaman_id
$query = $conn->query("DELETE FROM peminjaman WHERE peminjaman_id='$id'");

if ($query) {
    echo "<script>
            alert('Data Peminjaman Berhasil Dihapus');
            window.location.href = 'tampilan_peminjaman.php';
          </script>";
} else {
    echo "Data gagal dihapus: " . $conn->error;
}

?>
