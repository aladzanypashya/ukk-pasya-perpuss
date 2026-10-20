<?php
include "auth.php";
cek_login();
include "connection.php";
mysqli_report(MYSQLI_REPORT_OFF);

function h($s) { return htmlspecialchars((string)$s); }

// Hitung jumlah baris sebuah tabel (nama tabel dari daftar tetap, bukan input user)
function hitung($conn, $tabel, $where = "") {
    $q = mysqli_query($conn, "SELECT COUNT(*) AS total FROM `$tabel` $where");
    $r = $q ? mysqli_fetch_assoc($q) : null;
    return $r ? (int)$r['total'] : 0;
}

$staff = is_staff();
$uid   = (int)$_SESSION['id'];

// [ikon, judul, jumlah, link, warna]
if ($staff) {
    $transaksi = [
        ['⏳', 'Menunggu Persetujuan', hitung($conn, 'peminjaman', "WHERE status = 'diproses'"), 'tampilan_peminjaman.php', 'orange'],
        ['📚', 'Data Buku',            hitung($conn, 'buku'),         'tampilan.php',                'blue'],
        ['🔄', 'Data Peminjaman',      hitung($conn, 'peminjaman'),   'tampilan_peminjaman.php',     'purple'],
        ['✅', 'Data Pengembalian',    hitung($conn, 'pengembalian'), 'tampilan_pengembalian.php',   'green'],
    ];
    $master = [
        ['👥', 'Data Anggota',  hitung($conn, 'anggota'),  'tampilan_anggota.php', 'teal'],
        ['🏷️', 'Data Kategori', hitung($conn, 'kategori'), 'tampil_kategori.php',  'pink'],
        ['🏢', 'Data Penerbit', hitung($conn, 'penerbit'), 'tampilan_penerbit.php','indigo'],
        ['🔑', 'Data User',     hitung($conn, 'user'),     'tampil_user.php',      'gray'],
    ];
} else {
    $transaksi = [
        ['⏳', 'Pengajuan Saya (Diproses)', hitung($conn, 'peminjaman', "WHERE anggota_id = $uid AND status = 'diproses'"), 'tampilan_peminjaman.php', 'orange'],
        ['📚', 'Data Buku',         hitung($conn, 'buku'),         'tampilan.php',              'blue'],
        ['🔄', 'Data Peminjaman',   hitung($conn, 'peminjaman'),   'tampilan_peminjaman.php',   'purple'],
        ['✅', 'Data Pengembalian', hitung($conn, 'pengembalian'), 'tampilan_pengembalian.php', 'green'],
    ];
    $master = [];
}

// Panel daftar singkat: staff -> pengajuan yang menunggu, anggota -> 5 peminjaman terakhir miliknya
$rows = [];
if ($staff) {
    $q = mysqli_query($conn, "SELECT p.peminjaman_id, p.jumlah, p.tanggal_pinjam, p.status, b.judul, a.nama AS nama_anggota
                              FROM peminjaman p
                              LEFT JOIN buku b ON b.buku_id = p.buku_id
                              LEFT JOIN anggota a ON a.anggota_id = p.anggota_id
                              WHERE p.status = 'diproses'
                              ORDER BY p.peminjaman_id DESC LIMIT 5");
    $judul_panel = 'Pengajuan Menunggu Persetujuan';
} else {
    $q = mysqli_query($conn, "SELECT p.peminjaman_id, p.jumlah, p.tanggal_pinjam, p.status, b.judul
                              FROM peminjaman p
                              LEFT JOIN buku b ON b.buku_id = p.buku_id
                              WHERE p.anggota_id = $uid
                              ORDER BY p.peminjaman_id DESC LIMIT 5");
    $judul_panel = 'Peminjaman Saya Terakhir';
}
while ($q && $r = mysqli_fetch_assoc($q)) {
    $rows[] = $r;
}

$badge = [
    'diproses'           => ['Sedang Diproses',    'b-diproses'],
    'dipinjam'           => ['Dipinjam',           'b-dipinjam'],
    'sudah dikembalikan' => ['Sudah Dikembalikan', 'b-kembali'],
    'ditolak'            => ['Ditolak',            'b-ditolak'],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Perpustakaan</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #eef2f7; margin: 0; color: #1f2937; }
        .isi { max-width: 1000px; margin: 24px auto 40px; padding: 0 16px; }

        .banner { background: linear-gradient(120deg, #0b3d91, #2d7ff9); color: #fff; padding: 24px 28px; border-radius: 12px; }
        .banner h2 { margin: 0 0 6px; font-size: 22px; }
        .banner p { margin: 0; opacity: .9; font-size: 14px; }
        .chip { display: inline-block; background: rgba(255,255,255,.22); padding: 2px 12px; border-radius: 12px; font-weight: bold; text-transform: capitalize; }

        .warn { background: #fff3cd; color: #664d03; padding: 10px 14px; border-radius: 8px; margin-top: 14px; font-size: 14px; }

        h3.bagian { margin: 28px 0 12px; font-size: 13px; letter-spacing: .08em; text-transform: uppercase; color: #6b7280; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(215px, 1fr)); gap: 14px; }
        .card { display: flex; align-items: center; gap: 14px; background: #fff; padding: 16px; border-radius: 12px; text-decoration: none; color: inherit; box-shadow: 0 1px 4px rgba(0,0,0,.08); transition: transform .12s, box-shadow .12s; }
        .card:hover { transform: translateY(-2px); box-shadow: 0 6px 14px rgba(0,0,0,.12); }
        .ikon { width: 46px; height: 46px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0; }
        .angka { font-size: 26px; font-weight: bold; line-height: 1.1; }
        .label { font-size: 13px; color: #6b7280; margin-top: 2px; }
        .blue   { background: #dbeafe; } .purple { background: #ede9fe; } .green { background: #dcfce7; }
        .orange { background: #ffedd5; } .teal   { background: #ccfbf1; } .pink  { background: #fce7f3; }
        .indigo { background: #e0e7ff; } .gray   { background: #e5e7eb; }

        .panel { background: #fff; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,.08); padding: 18px; margin-top: 8px; }
        .panel-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        .panel-head b { font-size: 15px; }
        .panel-head a { font-size: 13px; color: #007BFF; text-decoration: none; }
        .tabel-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th { text-align: left; color: #6b7280; font-weight: bold; font-size: 12px; text-transform: uppercase; padding: 8px 10px; border-bottom: 1px solid #e5e7eb; }
        td { padding: 10px; border-bottom: 1px solid #f1f5f9; }
        tr:last-child td { border-bottom: 0; }
        .kosong { text-align: center; color: #9ca3af; padding: 18px 0; }

        .badge { display: inline-block; padding: 3px 10px; border-radius: 12px; font-size: 12px; font-weight: bold; white-space: nowrap; }
        .b-diproses { background: #fff3cd; color: #664d03; }
        .b-dipinjam { background: #cfe2ff; color: #084298; }
        .b-kembali  { background: #d1e7dd; color: #0f5132; }
        .b-ditolak  { background: #f8d7da; color: #842029; }
    </style>
</head>
<body>

<?php include "menu.php"; ?>

<div class="isi">

    <div class="banner">
        <h2>Selamat datang, <?php echo h($_SESSION['nama']); ?>!</h2>
        <p>Anda login sebagai <span class="chip"><?php echo h($_SESSION['role']); ?></span></p>
    </div>

    <?php if (isset($_GET['akses']) && $_GET['akses'] === 'ditolak') { ?>
        <div class="warn">Anda tidak memiliki akses ke halaman tersebut.</div>
    <?php } ?>

    <h3 class="bagian">Ringkasan</h3>
    <div class="grid">
        <?php foreach ($transaksi as $k) { ?>
            <a class="card" href="<?php echo $k[3]; ?>">
                <div class="ikon <?php echo $k[4]; ?>"><?php echo $k[0]; ?></div>
                <div>
                    <div class="angka"><?php echo $k[2]; ?></div>
                    <div class="label"><?php echo h($k[1]); ?></div>
                </div>
            </a>
        <?php } ?>
    </div>

    <?php if (!empty($master)) { ?>
        <h3 class="bagian">Data Master</h3>
        <div class="grid">
            <?php foreach ($master as $k) { ?>
                <a class="card" href="<?php echo $k[3]; ?>">
                    <div class="ikon <?php echo $k[4]; ?>"><?php echo $k[0]; ?></div>
                    <div>
                        <div class="angka"><?php echo $k[2]; ?></div>
                        <div class="label"><?php echo h($k[1]); ?></div>
                    </div>
                </a>
            <?php } ?>
        </div>
    <?php } ?>

    <h3 class="bagian"><?php echo h($judul_panel); ?></h3>
    <div class="panel">
        <div class="panel-head">
            <b><?php echo $staff ? 'Perlu di-ACC' : 'Status pengajuan Anda'; ?></b>
            <a href="tampilan_peminjaman.php">Lihat semua &rarr;</a>
        </div>
        <div class="tabel-wrap">
            <table>
                <tr>
                    <th>ID</th>
                    <?php if ($staff) { ?><th>Anggota</th><?php } ?>
                    <th>Buku</th>
                    <th>Jumlah</th>
                    <th>Tanggal Pinjam</th>
                    <th>Status</th>
                </tr>
                <?php if (empty($rows)) { ?>
                    <tr><td colspan="<?php echo $staff ? 6 : 5; ?>" class="kosong"><?php echo $staff ? 'Tidak ada pengajuan yang menunggu.' : 'Anda belum pernah mengajukan peminjaman.'; ?></td></tr>
                <?php } ?>
                <?php foreach ($rows as $r) {
                    $b = $badge[$r['status']] ?? [$r['status'], ''];
                ?>
                    <tr>
                        <td><?php echo h($r['peminjaman_id']); ?></td>
                        <?php if ($staff) { ?><td><?php echo h($r['nama_anggota'] ?? '-'); ?></td><?php } ?>
                        <td><?php echo h($r['judul'] ?? '-'); ?></td>
                        <td><?php echo h($r['jumlah']); ?></td>
                        <td><?php echo h($r['tanggal_pinjam']); ?></td>
                        <td><span class="badge <?php echo $b[1]; ?>"><?php echo h($b[0]); ?></span></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>

</div>

</body>
</html>
