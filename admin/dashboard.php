<?php
session_start();
include "../koneksi.php";

// Pastikan koneksi ada
if (!isset($koneksi) || !$koneksi) {
    die("Koneksi database gagal: " . (isset($koneksi) ? mysqli_connect_error() : "koneksi tidak tersedia"));
}

// Cek login
if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

$nama = $_SESSION['nama_user'];
$role = $_SESSION['role'];

// Fungsi aman untuk hitung jumlah baris di tabel
function getCount($koneksi, $tabel) {
    // Pastikan nama tabel berupa huruf/angka/underscore agar aman (sederhana)
    if (!preg_match('/^[a-z0-9_]+$/i', $tabel)) return 0;

    $sql = "SELECT COUNT(*) AS total FROM `$tabel`";
    $res = mysqli_query($koneksi, $sql);
    if (!$res) {
        // catat error ke console browser (developer) dan kembalikan 0
        $err = mysqli_error($koneksi);
        echo "<script>console.error('Query error pada tabel $tabel: " . addslashes($err) . "');</script>";
        return 0;
    }
    $row = mysqli_fetch_assoc($res);
    return (int)$row['total'];
}

// Ambil statistik hanya jika role = admin (menghindari query yang tak perlu untuk guru/santri)
$total_guru = $total_santri = $total_kelas = $total_mapel = $total_poin = 0;
$db_errors = [];

if ($role === 'admin') {
    // ambil tiap count dengan pengecekan aman
    $total_guru   = getCount($koneksi, "guru");
    $total_santri = getCount($koneksi, "santri");
    $total_kelas  = getCount($koneksi, "kelas");
    $total_mapel  = getCount($koneksi, "mapel");
    $total_poin   = getCount($koneksi, "riwayat_poin");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Dashboard - Catatan Poin Santri</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
    body { background-color: #eefff1ff; }
    .navbar { background-color: #115534ff; }
    .navbar-brand, .nav-link { color: white !important; }
    .card { border: none; border-radius: 15px; transition: 0.3s; }
    .card:hover { transform: translateY(-6px); box-shadow: 0 8px 20px rgba(0,0,0,0.1); }
    .card-icon { font-size: 40px; opacity: 0.8; }
    .footer { margin-top: 60px; padding: 15px; text-align: center; color: #666; font-size: 0.9rem; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#"><i class="bi bi-journal-check me-2"></i>Catatan Poin Santri</a>
        <div class="ms-auto">
        <span class="text-white me-3">Halo, <b><?= htmlspecialchars($nama); ?></b></span>
        <a href="../logout.php" class="btn btn-light btn-sm"><i class="bi bi-box-arrow-right me-1"></i>Logout</a>
        </div>
    </div>
    </nav>

    <div class="container mt-5">
    <h2 class="text-center mb-4 fw-bold">Dashboard <?= ucfirst(htmlspecialchars($role)); ?></h2>

    <?php if ($role === 'admin'): ?>
        <div class="row g-4">
        <div class="col-md-4 col-lg-3">
            <a href="data_guru.php" class="text-decoration-none">
            <div class="card text-white bg-primary text-center p-4">
                <div class="card-icon"><i class="bi bi-person-badge-fill"></i></div>
                <h3><?= $total_guru; ?></h3>
                <p class="fw-semibold">Guru</p>
            </div>
            </a>
        </div>

        <div class="col-md-4 col-lg-3">
            <a href="data_santri.php" class="text-decoration-none">
            <div class="card text-white bg-success text-center p-4">
                <div class="card-icon"><i class="bi bi-people-fill"></i></div>
                <h3><?= $total_santri; ?></h3>
                <p class="fw-semibold">Santri</p>
            </div>
            </a>
        </div>

        <div class="col-md-4 col-lg-3">
            <a href="data_kelas.php" class="text-decoration-none">
            <div class="card text-white bg-info text-center p-4">
                <div class="card-icon"><i class="bi bi-building-fill"></i></div>
                <h3><?= $total_kelas; ?></h3>
                <p class="fw-semibold">Kelas</p>
            </div>
            </a>
        </div>

        <div class="col-md-4 col-lg-3">
            <a href="data_mapel.php" class="text-decoration-none">
            <div class="card text-white bg-warning text-center p-4">
                <div class="card-icon"><i class="bi bi-book-half"></i></div>
                <h3><?= $total_mapel; ?></h3>
                <p class="fw-semibold">Mata Pelajaran</p>
            </div>
            </a>
        </div>

        <div class="col-md-4 col-lg-3">
            <a href="data_poin.php" class="text-decoration-none">
            <div class="card text-white bg-danger text-center p-4">
                <div class="card-icon"><i class="bi bi-award-fill"></i></div>
                <h3><?= $total_poin; ?></h3>
                <p class="fw-semibold">Kategori Poin</p>
            </div>
            </a>
        </div>
        </div>

        <div class="text-center mt-5">
        <a href="data_guru.php" class="btn btn-outline-primary m-1">Kelola Guru</a>
        <a href="data_santri.php" class="btn btn-outline-success m-1">Kelola Santri</a>
        <a href="data_kelas.php" class="btn btn-outline-info m-1">Kelola Kelas</a>
        <a href="data_mapel.php" class="btn btn-outline-warning m-1">Kelola Mapel</a>
        <a href="data_poin.php" class="btn btn-outline-danger m-1">Kelola Poin</a>
        </div>

    <?php elseif ($role === 'guru'): ?>

        <div class="row g-4 justify-content-center">
        <div class="col-md-4">
            <div class="card text-white bg-warning text-center p-4">
            <div class="card-icon"><i class="bi bi-pencil-square"></i></div>
            <h4>Tambah Nilai</h4>
            <p class="fw-semibold">Input nilai santri berdasarkan mata pelajaran Anda.</p>
            <a href="../guru/tambah_nilai.php" class="btn btn-light btn-sm">Buka</a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success text-center p-4">
            <div class="card-icon"><i class="bi bi-plus-circle"></i></div>
            <h4>Tambah Poin</h4>
            <p class="fw-semibold">Tambahkan poin pelanggaran atau penghargaan santri.</p>
            <a href="../guru/tambah_poin.php" class="btn btn-light btn-sm">Buka</a>
            </div>
        </div>
        </div>

    <?php else: /* santri atau role lain */ ?>

        <div class="text-center">
        <div class="card border-0 shadow-sm p-4 mx-auto" style="max-width:500px;">
            <i class="bi bi-person-circle text-success fs-1 mb-3"></i>
            <h4 class="fw-bold"><?= htmlspecialchars($nama); ?></h4>
            <p class="text-muted">Lihat nilai dan poin kamu di bawah ini.</p>
            <a href="../santri/lihat_nilai.php" class="btn btn-success w-100 mb-2">Lihat Nilai</a>
            <a href="../santri/lihat_poin.php" class="btn btn-warning w-100 text-white">Lihat Poin</a>
        </div>
        </div>

    <?php endif; ?>
    </div>

    <div class="footer">
    <p>© <?= date('Y'); ?> Catatan Poin Santri | Developed by <b>Ranti Amanda Rizkia</b></p>
    </div>

</body>
</html>
