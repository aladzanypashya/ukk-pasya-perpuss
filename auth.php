<?php
// auth.php - helper sesi & hak akses. Di-include di paling atas setiap halaman yang diproteksi.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Sudah login atau belum
function sudah_login() {
    return isset($_SESSION['role']);
}

// Admin atau petugas
function is_staff() {
    return sudah_login() && in_array($_SESSION['role'], ['admin', 'petugas']);
}

// Halaman yang boleh dibuka semua yang sudah login (admin, petugas, anggota)
function cek_login() {
    if (!sudah_login()) {
        header("Location: login.php");
        exit;
    }
}

// Halaman khusus admin/petugas
function cek_staff() {
    cek_login();
    if (!is_staff()) {
        header("Location: dashboard.php?akses=ditolak");
        exit;
    }
}

// Cocokkan password. Mendukung password biasa (sesuai data project sekarang)
// dan password hash (password_hash) kalau nanti Anda ubah.
function cek_password($input, $tersimpan) {
    if (preg_match('/^\$(2[ayb]|argon2)/', $tersimpan)) {
        return password_verify($input, $tersimpan);
    }
    return hash_equals((string)$tersimpan, (string)$input);
}
?>
