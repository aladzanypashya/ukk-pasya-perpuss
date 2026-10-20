<?php include 'auth.php'; cek_staff(); ?>
<?php
include "connection.php";

if (isset($_POST['simpan'])) {
    
    // Ambil data dan paksa ubah tipe datanya menjadi Angka (Integer)
    $peminjaman_id   = intval($_POST['peminjaman_id']);
    $tanggal_kembali = $_POST['tanggal_kembali'];
    $user_id         = intval($_POST['user_id']);

    // Cek apakah ID Peminjaman dan User bernilai valid (> 0)
    if ($peminjaman_id > 0 && $user_id > 0) {
        
        // Query SIMPAN DATA
        $sql = "INSERT INTO pengembalian (peminjaman_id, tanggal_kembali, user_id) 
                VALUES ('$peminjaman_id', '$tanggal_kembali', '$user_id')";

        if (mysqli_query($conn, $sql)) {
            
            // Update status di tabel peminjaman
            mysqli_query($conn, "UPDATE peminjaman SET status='sudah dikembalikan' WHERE peminjaman_id='$peminjaman_id'");

            echo "<script>
                    alert('Data Pengembalian Berhasil Ditambahkan!');
                    window.location.href='tampilan_pengembalian.php';
                  </script>";
        } else {
            echo "Gagal menambahkan data ke Database: " . mysqli_error($conn);
        }

    } else {
        echo "<script>
                alert('ID Peminjaman dan User ID harus berupa angka!');
                window.history.back();
              </script>";
    }

} else {
    header("Location: tambah_pengembalian.php");
}
?>
