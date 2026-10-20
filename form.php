<?php include "auth.php"; wajib_staff(); ?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Tambah Data Buku</title></head>
<body>

<h1>Tambah Data Buku</h1>

<form action="simpan.php" method="post" enctype="multipart/form-data">

  <label>Kode Buku:</label><br>
  <input type="text" name="kode_buku" required><br><br>

  <label>Judul Buku:</label><br>
  <input type="text" name="judul" required><br><br>

  <label>Kategori ID:</label><br>
  <input type="number" name="kategori_id" required><br><br>

  <label>Penerbit ID:</label><br>
  <input type="number" name="penerbit_id" required><br><br>

  <label>ISBN:</label><br>
  <input type="text" name="isbn"><br><br>

  <label>Pengarang:</label><br>
  <input type="text" name="pengarang"><br><br>

  <label>Jumlah Halaman:</label><br>
  <input type="number" name="jumlah_halaman"><br><br>

  <label>Jumlah Stok:</label><br>
  <input type="number" name="jumlah_stok"><br><br>

  <label>Tahun Terbit:</label><br>
  <input type="number" name="tahun_terbit"><br><br>

  <label>Sinopsis:</label><br>
  <textarea name="sinopsis" rows="4" cols="30"></textarea><br><br>

  <label>Foto Buku (JPG/PNG/WEBP, maks. 2 MB):</label><br>
  <input type="file" name="gambar" accept="image/jpeg,image/png,image/webp"><br><br>

  <button type="submit">Simpan</button>
  <button type="reset">Reset</button>
  <a href="tampilan.php"><button type="button">Kembali</button></a>

</form>

</body>
</html>
