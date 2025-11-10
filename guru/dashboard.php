<?php
session_start();
include "../koneksi.php";

// Pastikan hanya guru yang bisa mengakses
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'guru') {
    header("Location: ../login.php");
    exit;
}

$id_guru = $_SESSION['id_guru'];

// Ambil data guru
$guru = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM guru WHERE id_guru='$id_guru'"));

// Ambil daftar santri
$santri = mysqli_query($koneksi, "SELECT * FROM santri ORDER BY nama ASC");

// Tambah nilai santri
if (isset($_POST['tambah_nilai'])) {
    $id_santri = $_POST['id_santri'];
    $mapel = $_POST['mata_pelajaran'];
    $nilai = $_POST['nilai'];

    mysqli_query($koneksi, "INSERT INTO nilai (id_santri, guru, mata_pelajaran, nilai) 
        VALUES ('$id_santri', '{$guru['nama_guru']}', '$mapel', '$nilai')");
    header("Location: dashboard_guru.php");
    exit;
}

// Tambah poin santri
if (isset($_POST['tambah_poin'])) {
    $id_santri = $_POST['id_santri'];
    $keterangan = $_POST['keterangan'];
    $poin = $_POST['jumlah_poin'];
    $tanggal = date("Y-m-d");

    mysqli_query($koneksi, "INSERT INTO poin (id_santri, keterangan, jumlah_poin, tanggal) 
        VALUES ('$id_santri', '$keterangan', '$poin', '$tanggal')");
    header("Location: dashboard_guru.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Guru | Catatan Poin Santri</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f9fafb;
        }
        .sidebar {
            background: linear-gradient(180deg, #198754, #157347);
            min-height: 100vh;
            color: white;
        }
        .sidebar h4 {
            padding: 20px 0;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }
        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
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
            <h4>Guru Dashboard</h4>
            <a href="#">🏠 Beranda</a>
            <a href="#santri">🧍 Data Santri</a>
            <a href="#nilai">📘 Input Nilai</a>
            <a href="#poin">⭐ Tambah Poin</a>
            <a href="logout.php" class="text-danger">🚪 Keluar</a>
        </div>

        <!-- Konten Utama -->
        <div class="col-md-9 col-lg-10 p-4">
            <h3 class="fw-bold text-success mb-4">Selamat Datang, <?= htmlspecialchars($guru['nama_guru']); ?> 👋</h3>
            <p class="text-secondary mb-5">Anda mengajar mata pelajaran: <strong><?= htmlspecialchars($guru['mata_pelajaran']); ?></strong></p>

            <!-- Data Santri -->
            <section id="santri" class="mb-5">
                <div class="card p-3">
                    <h5 class="text-success fw-bold mb-3">🧍 Daftar Santri</h5>
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>NIS</th>
                                <th>Kelas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            while ($row = mysqli_fetch_assoc($santri)): 
                            ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= htmlspecialchars($row['nama']); ?></td>
                                <td><?= htmlspecialchars($row['nis']); ?></td>
                                <td><?= htmlspecialchars($row['kelas']); ?></td>
                                <td>
                                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#nilaiModal<?= $row['id_santri']; ?>">+ Nilai</button>
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#poinModal<?= $row['id_santri']; ?>">+ Poin</button>
                                </td>
                            </tr>

                            <!-- Modal Input Nilai -->
                            <div class="modal fade" id="nilaiModal<?= $row['id_santri']; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form method="POST">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Input Nilai - <?= htmlspecialchars($row['nama']); ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="id_santri" value="<?= $row['id_santri']; ?>">
                                                <div class="mb-3">
                                                    <label>Mata Pelajaran</label>
                                                    <input type="text" name="mata_pelajaran" class="form-control" value="<?= htmlspecialchars($guru['mata_pelajaran']); ?>" readonly>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Nilai</label>
                                                    <input type="number" name="nilai" class="form-control" min="0" max="100" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" name="tambah_nilai" class="btn btn-success">Simpan</button>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Tambah Poin -->
                            <div class="modal fade" id="poinModal<?= $row['id_santri']; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form method="POST">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Tambah Poin - <?= htmlspecialchars($row['nama']); ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="id_santri" value="<?= $row['id_santri']; ?>">
                                                <div class="mb-3">
                                                    <label>Keterangan</label>
                                                    <input type="text" name="keterangan" class="form-control" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Jumlah Poin</label>
                                                    <input type="number" name="jumlah_poin" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" name="tambah_poin" class="btn btn-warning">Simpan</button>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
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
