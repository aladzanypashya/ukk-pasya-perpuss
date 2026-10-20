<?php include "auth.php"; wajib_staff(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Tambah Data Anggota</title>
</head>
<body>

<h1>Tambah Data Anggota</h1>

<form action="simpan_anggota.php" method="post">

  <label>Id:</label><br>
  <input type="text" name="anggota_id" required><br><br>

  <label>Kode Anggota:</label><br>
  <input type="text" name="kode_anggota" required><br><br>

  <label>Nama Anggota:</label><br>
  <input type="text" name="nama" required><br><br>

  <label>Password:</label><br>
  <input type="text" name="password" required><br><br>
  
  <label>Jenis Kelamin:</label><br>
  <select name="jenis_kelamin" required>
      <option value="">-- Pilih Jenis Kelamin --</option>
      <option value="pria">Pria</option>
      <option value="wanita">Wanita</option>
  </select><br><br>

  <label>Tempat Lahir:</label><br>
  <input type="text" name="tempat_lahir"><br><br>

  <label>Tanggal Lahir:</label><br>
  <input type="date" name="tanggal_lahir"><br><br>

  <label>No. Telpon:</label><br>
  <input type="text" name="telpon"><br><br>

  <label>Alamat:</label><br>
  <textarea name="alamat" rows="4" cols="30"></textarea><br><br>

  <label>Foto (Nama File):</label><br>
  <input type="text" name="foto"><br><br>

  <button type="submit">Simpan</button>
  <button type="reset">Reset</button>

  <a href="tampilan_anggota.php">
    <button type="button">Kembali</button>
  </a>

</form>

</body>
</html>
