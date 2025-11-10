<?php
session_start();
include "../koneksi.php";

// Pastikan hanya santri yang bisa akses halaman ini
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'santri') {
    header("Location: ../login.php");
    exit;
}

$id_santri = $_SESSION['id_santri'];

// Ambil biodata santri
$biodata = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM santri WHERE id_santri='$id_santri'"));

// Ambil data nilai dan poin santri
$nilai = mysqli_query($koneksi, "SELECT * FROM nilai WHERE id_santri='$id_santri'");
$poin = mysqli_query($koneksi, "SELECT * FROM poin WHERE id_santri='$id_santri'");

$total_poin = 0;
while ($p = mysqli_fetch_assoc($poin)) {
    $total_poin += $p['jumlah_poin'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Santri | Catatan Poin Santri</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f9fafb;
        }
        .sidebar {
            background: linear-gradient(180deg, #198754, #157347);
            min-height: 100vh;
            color: white;
        }
        .sidebar h4 {
            text-align: center;
            padding: 20px 0;
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }
        .sidebar a {
            display: block;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
        }
        .sidebar a:hover {
            background: rgba(255,255,255,0.2);
            border-radius: 5px;
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
        }
        .table th {
            background-color: #198754;
            color: white;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 sidebar p-0">
            <h4>Santri Dashboard</h4>
            <a href="#">🏠 Beranda</a>
            <a href="#biodata">🧍 Biodata</a>
            <a href="#nilai">📘 Nilai</a>
            <a href="#poin">⭐ Poin</a>
            <a href="logout.php" class="text-danger">🚪 Keluar</a>
        </div>

        <!-- Konten -->
        <div class="col-md-9 col-lg-10 p-4">
            <h3 class="fw-bold text-success mb-4">Selamat Datang, <?= htmlspecialchars($biodata['nama']); ?> 👋</h3>

            <!-- Biodata Santri -->
            <section id="biodata" class="mb-5">
                <div class="card p-3">
                    <h5 class="text-success fw-bold mb-3">🧍 Biodata Santri</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th>Nama</th>
                            <td><?= htmlspecialchars($biodata['nama']); ?></td>
                        </tr>
                        <tr>
                            <th>NIS</th>
                            <td><?= htmlspecialchars($biodata['nis']); ?></td>
                        </tr>
                        <tr>
                            <th>Kelas</th>
                            <td><?= htmlspecialchars($biodata['kelas']); ?></td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td><?= htmlspecialchars($biodata['alamat']); ?></td>
                        </tr>
                    </table>
                </div>
            </section>

            <!-- Nilai Santri -->
            <section id="nilai" class="mb-5">
                <div class="card p-3">
                    <h5 class="text-success fw-bold mb-3">📘 Nilai Santri</h5>
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Mata Pelajaran</th>
                                <th>Nilai</th>
                                <th>Guru</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            while ($n = mysqli_fetch_assoc($nilai)): 
                            ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= htmlspecialchars($n['mata_pelajaran']); ?></td>
                                <td><?= htmlspecialchars($n['nilai']); ?></td>
                                <td><?= htmlspecialchars($n['guru']); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Poin Santri -->
            <section id="poin">
                <div class="card p-3">
                    <h5 class="text-success fw-bold mb-3">⭐ Catatan Poin Santri</h5>
                    <p class="fw-semibold">Total Poin: 
                        <span class="badge bg-success p-2"><?= $total_poin; ?></span>
                    </p>
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Keterangan</th>
                                <th>Jumlah Poin</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            $poin = mysqli_query($koneksi, "SELECT * FROM poin WHERE id_santri='$id_santri'");
                            while ($p = mysqli_fetch_assoc($poin)): 
                            ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= htmlspecialchars($p['keterangan']); ?></td>
                                <td><?= htmlspecialchars($p['jumlah_poin']); ?></td>
                                <td><?= htmlspecialchars($p['tanggal']); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
