<?php include 'auth.php'; cek_staff(); ?>
<?php

include "connection.php";

// Menangkap data dari form
$buku           = $_POST['kode_buku'];
$judul          = $_POST['judul'];
$kategori       = $_POST['kategori_id'];
$isbn           = $_POST['isbn'];
$pengarang      = $_POST['pengarang'];
$jumlah_halaman = $_POST['jumlah_halaman']; // Membedakan nama variabel
$jumlah_stok    = $_POST['jumlah_stok'];    // Membedakan nama variabel
$penerbit       = $_POST['penerbit_id'];
$tahun          = $_POST['tahun_terbit'];
$sinopsis       = $_POST['sinopsis'];
$gambar         = $_POST['gambar'];

// Query INSERT (buku_id tidak dimasukkan karena diset AUTO_INCREMENT di database)
$sql = "INSERT INTO buku 
        (kode_buku, judul, kategori_id, isbn, pengarang, jumlah_halaman, jumlah_stok, penerbit_id, tahun_terbit, sinopsis, gambar)
        VALUES 
        ('$buku', '$judul', '$kategori', '$isbn', '$pengarang', '$jumlah_halaman', '$jumlah_stok', '$penerbit', '$tahun', '$sinopsis', '$gambar')";

if (mysqli_query($conn, $sql)) {
    echo "<script>
            alert('Data Berhasil Ditambahkan');
            location='tampilan.php';
          </script>";
} else {
    echo "Gagal menambahkan data: " . mysqli_error($conn);
}

?>
