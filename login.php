<?php
include "connection.php";
include "auth.php";

// Kalau sudah login, langsung ke dashboard
if (sudah_login()) {
    header("Location: dashboard.php");
    exit;
}

$error = "";
$peran = "staff"; // default pilihan

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $peran      = $_POST['peran'] ?? 'staff';
    $identitas  = trim($_POST['identitas'] ?? '');
    $password   = $_POST['password'] ?? '';

    if ($identitas === '' || $password === '') {
        $error = "Semua kolom wajib diisi.";
    } elseif ($peran === 'staff') {
        // Login admin / petugas -> tabel user (username)
        $stmt = mysqli_prepare($conn, "SELECT user_id, nama, password, level FROM user WHERE username = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, "s", $identitas);
        mysqli_stmt_execute($stmt);
        $hasil = mysqli_stmt_get_result($stmt);
        $row   = mysqli_fetch_assoc($hasil);

        if ($row && cek_password($password, $row['password']) && in_array($row['level'], ['admin', 'petugas'])) {
            session_regenerate_id(true);
            $_SESSION['id']   = $row['user_id'];
            $_SESSION['nama'] = $row['nama'];
            $_SESSION['role'] = $row['level']; // 'admin' atau 'petugas'
            header("Location: dashboard.php");
            exit;
        }
        $error = "Username atau password salah.";
    } elseif ($peran === 'anggota') {
        // Login anggota -> tabel anggota (kode_anggota)
        $stmt = mysqli_prepare($conn, "SELECT anggota_id, nama, password FROM anggota WHERE kode_anggota = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, "s", $identitas);
        mysqli_stmt_execute($stmt);
        $hasil = mysqli_stmt_get_result($stmt);
        $row   = mysqli_fetch_assoc($hasil);

        if ($row && cek_password($password, $row['password'])) {
            session_regenerate_id(true);
            $_SESSION['id']   = $row['anggota_id'];
            $_SESSION['nama'] = $row['nama'];
            $_SESSION['role'] = 'anggota';
            header("Location: dashboard.php");
            exit;
        }
        $error = "Kode anggota atau password salah.";
    } else {
        $error = "Pilihan login tidak valid.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Perpustakaan</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .box { background: #fff; width: 100%; max-width: 360px; padding: 28px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,.12); }
        h2 { text-align: center; color: navy; margin: 0 0 20px; }
        label { display: block; margin: 12px 0 4px; font-size: 14px; }
        input, select { width: 100%; padding: 9px; border: 1px solid #bbb; border-radius: 4px; font-size: 14px; }
        button { width: 100%; margin-top: 20px; padding: 10px; background: #007BFF; color: #fff; border: 0; border-radius: 4px; font-size: 15px; cursor: pointer; }
        button:hover { background: #0062cc; }
        .error { background: #f8d7da; color: #842029; padding: 9px; border-radius: 4px; font-size: 14px; margin-bottom: 8px; }
    </style>
</head>
<body>
<div class="box">
    <h2>Login Perpustakaan</h2>

    <?php if ($error) { ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php } ?>

    <form method="post" autocomplete="off">
        <label for="peran">Login sebagai</label>
        <select name="peran" id="peran" onchange="ubahLabel()">
            <option value="staff"   <?php if ($peran === 'staff')   echo 'selected'; ?>>Admin / Petugas</option>
            <option value="anggota" <?php if ($peran === 'anggota') echo 'selected'; ?>>Anggota</option>
        </select>

        <label for="identitas" id="labelIdentitas">Username</label>
        <input type="text" name="identitas" id="identitas" value="<?php echo htmlspecialchars($_POST['identitas'] ?? ''); ?>" required>

        <label for="password">Password</label>
        <input type="password" name="password" id="password" required>

        <button type="submit">Login</button>
    </form>

    <p style="text-align:center; font-size:14px; margin-top:16px; margin-bottom:0;">
        Belum punya akun anggota? <a href="register.php" style="color:#007BFF;">Daftar di sini</a>
    </p>
</div>

<script>
function ubahLabel() {
    var peran = document.getElementById('peran').value;
    document.getElementById('labelIdentitas').textContent = (peran === 'anggota') ? 'Kode Anggota' : 'Username';
}
ubahLabel();
</script>
</body>
</html>
