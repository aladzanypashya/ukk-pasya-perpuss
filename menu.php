<?php
// menu.php - navigasi atas. Admin/petugas melihat semua menu, anggota hanya 3 menu.
if (!function_exists('is_staff')) { include_once "auth.php"; }
?>
<style>
.nav { background: #0b3d91; padding: 10px 16px; display: flex; flex-wrap: wrap; align-items: center; gap: 4px; font-family: Arial, sans-serif; }
.nav a { color: #fff; text-decoration: none; padding: 6px 10px; border-radius: 4px; font-size: 14px; }
.nav a:hover { background: rgba(255,255,255,.2); }
.nav .kanan { margin-left: auto; color: #fff; font-size: 14px; }
.nav .kanan a { background: #dc3545; margin-left: 8px; }
</style>
<div class="nav">
    <a href="dashboard.php">Dashboard</a>
    <a href="tampilan.php">Buku</a>
    <a href="tampilan_peminjaman.php">Peminjaman</a>
    <a href="tampilan_pengembalian.php">Pengembalian</a>
    <?php if (!is_staff()) { ?>
        <a href="ajukan_peminjaman.php">Ajukan Pinjam</a>
    <?php } ?>
    <?php if (is_staff()) { ?>
        <a href="tampilan_anggota.php">Anggota</a>
        <a href="tampil_kategori.php">Kategori</a>
        <a href="tampilan_penerbit.php">Penerbit</a>
        <a href="tampil_user.php">User</a>
    <?php } ?>
    <span class="kanan">
        <?php echo htmlspecialchars($_SESSION['nama'] ?? ''); ?> (<?php echo htmlspecialchars($_SESSION['role'] ?? ''); ?>)
        <a href="logout.php" onclick="return confirm('Yakin ingin logout?')">Logout</a>
    </span>
</div>
