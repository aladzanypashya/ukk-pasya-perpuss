<?php include 'auth.php'; cek_staff(); ?>
<?php

include "connection.php";

// Menangkap ID dari URL
$id = $_GET['id'];

// Query DELETE berdasarkan user_id
$query = $conn->query("DELETE FROM user WHERE user_id='$id'");

if ($query) {
    echo "<script>
            alert('Data Berhasil Dihapus');
            window.location.href = 'tampilan_user.php';
          </script>";
} else {
    echo "Data gagal dihapus: " . $conn->error;
}

?>
