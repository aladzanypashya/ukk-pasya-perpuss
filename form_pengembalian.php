<?php include "auth.php"; wajib_staff(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Pengembalian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            padding: 20px;
        }
        .form-container {
            background: #ffffff;
            padding: 25px;
            border-radius: 8px;
            max-width: 400px;
            margin: 30px auto;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .form-container h2 {
            margin-bottom: 20px;
            color: #1b365d;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        input[type="number"],
        input[type="date"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .btn-group {
            margin-top: 20px;
        }
        .btn {
            padding: 9px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            color: white;
            text-decoration: none;
            display: inline-block;
        }
        .btn-simpan {
            background-color: #1b365d;
        }
        .btn-reset {
            background-color: #e74c3c;
        }
        .btn-kembali {
            background-color: #6c757d;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Tambah Data Pengembalian</h2>

    <!-- Form tetap mengirim data ke simpan_pengembalian.php untuk diproses ke database -->
    <form action="simpan_pengembalian.php" method="POST">
        
        <div class="form-group">
            <label for="peminjaman_id">ID Peminjaman:</label>
            <input type="number" id="peminjaman_id" name="peminjaman_id" placeholder="Masukkan ID Peminjaman" required>
        </div>

        <div class="form-group">
            <label for="tanggal_kembali">Tanggal Kembali:</label>
            <input type="date" id="tanggal_kembali" name="tanggal_kembali" required>
        </div>

        <div class="form-group">
            <label for="user_id">ID Petugas (User ID):</label>
            <input type="number" id="user_id" name="user_id" placeholder="Masukkan ID User" required>
        </div>

        <div class="btn-group">
            <button type="submit" name="simpan" class="btn btn-simpan">Simpan Data</button>
            <button type="reset" class="btn btn-reset">Reset</button>
            <a href="tampilan_pengembalian.php" class="btn btn-kembali">Kembali</a>
        </div>

    </form>
</div>

</body>
</html>
