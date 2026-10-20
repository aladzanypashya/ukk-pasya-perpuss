<?php include 'auth.php'; cek_staff(); ?>
<?php

include "connection.php";

// Menangkap ID dari URL
$id = $_GET['id'];

// Query DELETE berdasarkan pengembalian_id
$query = $conn->query("DELETE FROM pengembalian WHERE pengembalian_id='$id'");

if ($query) {
    echo "<script>
            alert('Data Pengembalian Berhasil Dihapus');
            window.location.href = 'tampilan_pengembalian.php';
          </script>";
} else {
    echo "Data gagal dihapus: " . $conn->error;
}

?>
