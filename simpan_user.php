<?php include 'auth.php'; cek_staff(); ?>
<?php

include "connection.php";

// Menangkap data dari form
$nama     = $_POST['nama'];
$username = $_POST['username'];
$password = $_POST['password'];
$level    = $_POST['level'];

// Query INSERT (user_id AUTO_INCREMENT)
$sql = "INSERT INTO user (nama, username, password, level) VALUES ('$nama', '$username', '$password', '$level')";

if (mysqli_query($conn, $sql)) {
    echo "<script>
            alert('Data Berhasil Ditambahkan');
            location='tampilan_user.php';
          </script>";
} else {
    echo "Gagal menambahkan data: " . mysqli_error($conn);
}

?>
