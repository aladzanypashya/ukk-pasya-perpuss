<?php include 'auth.php'; cek_staff(); ?>
<?php

include "connection.php";

// Menangkap ID dari URL
$id = $_GET['id'];

// Query DELETE berdasarkan penerbit_id
$query = $conn->query("DELETE FROM penerbit WHERE penerbit_id='$id'");

if ($query) {
    echo "<script>
            alert('Data Berhasil Dihapus');
            window.location.href = 'tampilan_penerbit.php';
          </script>";
} else {
    echo "Data gagal dihapus: " . $conn->error;
}

?>
