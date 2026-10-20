<?php include 'auth.php'; cek_staff(); ?>
<?php

include "connection.php";

// Menangkap data dari form
$id   =     $_POST['anggota_id'];
$kode_anggota   = $_POST['kode_anggota'];
$nama           = $_POST['nama'];
$password       = $_POST['password']; // Menangkap data password dari form
$jenis_kelamin  = $_POST['jenis_kelamin'];
$tempat_lahir   = $_POST['tempat_lahir'];
$tanggal_lahir  = $_POST['tanggal_lahir'];
$telpon         = $_POST['telpon'];
$alamat         = $_POST['alamat'];
$foto           = $_POST['foto'];

// Query INSERT dengan tambahan kolom password
$sql = "INSERT INTO anggota 
        (anggota_id, kode_anggota, nama, password, jenis_kelamin, tempat_lahir, tanggal_lahir, telpon, alamat, foto)
        VALUES 
        ('$anggota_id','$kode_anggota', '$nama', '$password', '$jenis_kelamin', '$tempat_lahir', '$tanggal_lahir', '$telpon', '$alamat', '$foto')";

if (mysqli_query($conn, $sql)) {
    echo "<script>
            alert('Data Berhasil Ditambahkan');
            location='tampilan.php';
          </script>";
} else {
    echo "Gagal menambahkan data: " . mysqli_error($conn);
}

?>
