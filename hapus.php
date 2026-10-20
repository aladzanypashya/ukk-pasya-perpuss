<?php include 'auth.php'; cek_staff(); ?>
<?php

include "connection.php";


$id = $_GET['id'];


$query = $conn->query("DELETE FROM buku WHERE buku_id='$id'");

if ($query) {
    echo "<script>
            alert('Data Berhasil Dihapus');
            window.location.href = 'tampilan.php';
          </script>";
} else {
    echo "Data gagal dihapus: " . $conn->error;
}

?>
