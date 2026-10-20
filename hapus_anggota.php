<?php include 'auth.php'; cek_staff(); ?>
<?php

include "connection.php";

// Menangkap ID dari URL (anggota_id)
$id = $_GET['id'];

// Query DELETE berdasarkan anggota_id
$query = $conn->query("DELETE FROM anggota WHERE anggota_id='$id'");

if ($query) {
    echo "<script>
            alert('Data Anggota Berhasil Dihapus');
            window.location.href = 'tampilan.php';
          </script>";
} else {
    echo "Data gagal dihapus: " . $conn->error;
}

?>
