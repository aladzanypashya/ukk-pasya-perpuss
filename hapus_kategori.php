<?php include 'auth.php'; cek_staff(); ?>
<?php

include "connection.php";

// Menangkap ID dari URL
$id = $_GET['id'];

// Query DELETE berdasarkan kategori_id
$query = $conn->query("DELETE FROM kategori WHERE kategori_id='$id'");

if ($query) {
    echo "<script>
            alert('Data Berhasil Dihapus');
            window.location.href = 'tampilan_kategori.php';
          </script>";
} else {
    echo "Data gagal dihapus: " . $conn->error;
}

?>
