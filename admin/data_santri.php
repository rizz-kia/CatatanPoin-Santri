<?php
include "../koneksi.php";
session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

// --- Tambah Data ---
if (isset($_POST['tambah'])) {
    $nis = $_POST['nis'];
    $nama = $_POST['nama_santri'];
    $id_kelas = $_POST['id_kelas'];
    $jk = $_POST['jenis_kelamin'];
    $tgl = $_POST['tanggal_lahir'];
    $wali = $_POST['wali'];
    $kontak = $_POST['kontak_wali'];
    $status = $_POST['status'];

    // total_poin otomatis 0 saat pertama kali ditambah
    $query = "INSERT INTO santri (nis, nama_santri, id_kelas, jenis_kelamin, tanggal_lahir, wali, kontak_wali, status, total_poin)
              VALUES ('$nis','$nama','$id_kelas','$jk','$tgl','$wali','$kontak','$status', 0)";
    if (!mysqli_query($koneksi, $query)) {
        die("Error query: " . mysqli_error($koneksi));
    }
    header("Location: data_santri.php");
    exit;
}

// --- Hapus Data ---
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM santri WHERE id_santri='$id'");
    header("Location: data_santri.php");
    exit;
}

// --- Edit Data ---
if (isset($_POST['update'])) {
    $id = $_POST['id_santri'];
    $nis = $_POST['nis'];
    $nama = $_POST['nama_santri'];
    $id_kelas = $_POST['id_kelas'];
    $jk = $_POST['jenis_kelamin'];
    $tgl = $_POST['tanggal_lahir'];
    $wali = $_POST['wali'];
    $kontak = $_POST['kontak_wali'];
    $status = $_POST['status'];

    $update = "UPDATE santri SET 
                nis='$nis', nama_santri='$nama', id_kelas='$id_kelas',
                jenis_kelamin='$jk', tanggal_lahir='$tgl', wali='$wali',
                kontak_wali='$kontak', status='$status'
              WHERE id_santri='$id'";
    if (!mysqli_query($koneksi, $update)) {
        die("Error update: " . mysqli_error($koneksi));
    }
    header("Location: data_santri.php");
    exit;
}

// Ambil semua data santri
$santri = mysqli_query($koneksi, "SELECT * FROM santri ORDER BY id_santri DESC");

// Jika sedang edit
$editData = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $editData = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM santri WHERE id_santri='$id'"));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Data Santri</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">
    <h2 class="text-success mb-4 fw-bold"><i class="bi bi-people-fill me-2"></i>Data Santri</h2>

    <!-- Tombol Tambah -->
    <button class="btn btn-success mb-3" data-bs-toggle="collapse" data-bs-target="#formSantri">
        <i class="bi bi-plus-circle me-1"></i><?= $editData ? 'Edit Santri' : 'Tambah Santri' ?>
    </button>
    <a href="../admin/dashboard.php" class="btn btn-secondary mb-3"><i class="bi bi-arrow-left"></i> Kembali</a>

    <!-- Form Tambah / Edit -->
    <div id="formSantri" class="collapse show">
        <div class="card card-body shadow-sm">
            <form method="post">
                <?php if ($editData): ?>
                    <input type="hidden" name="id_santri" value="<?= $editData['id_santri']; ?>">
                <?php endif; ?>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label>NIS</label>
                        <input type="text" name="nis" class="form-control" value="<?= $editData['nis'] ?? ''; ?>" required>
                    </div>
                    <div class="col-md-5">
                        <label>Nama Santri</label>
                        <input type="text" name="nama_santri" class="form-control" value="<?= $editData['nama_santri'] ?? ''; ?>" required>
                    </div>
                    <div class="col-md-2">
                        <label>Kelas</label>
                        <select name="id_kelas" class="form-select" required>
                            <option value="">Pilih Kelas</option>
                            <?php
                            $kelas = mysqli_query($koneksi, "SELECT * FROM kelas ORDER BY id_kelas ASC");
                            while ($k = mysqli_fetch_assoc($kelas)) {
                                $selected = isset($editData) && $editData['id_kelas'] == $k['id_kelas'] ? 'selected' : '';
                                echo "<option value='{$k['id_kelas']}' $selected>{$k['nama_kelas']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-select" required>
                            <option value="Laki-laki" <?= isset($editData) && $editData['jenis_kelamin']=='Laki-laki' ? 'selected' : ''; ?>>Laki-laki</option>
                            <option value="Perempuan" <?= isset($editData) && $editData['jenis_kelamin']=='Perempuan' ? 'selected' : ''; ?>>Perempuan</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-control" value="<?= $editData['tanggal_lahir'] ?? ''; ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label>Nama Wali</label>
                        <input type="text" name="wali" class="form-control" value="<?= $editData['wali'] ?? ''; ?>">
                    </div>
                    <div class="col-md-3">
                        <label>Kontak Wali</label>
                        <input type="text" name="kontak_wali" class="form-control" value="<?= $editData['kontak_wali'] ?? ''; ?>">
                    </div>
                    <div class="col-md-3">
                        <label>Status</label>
                        <select name="status" class="form-select">
                            <option <?= isset($editData) && $editData['status']=='Aktif'?'selected':''; ?>>Aktif</option>
                            <option <?= isset($editData) && $editData['status']=='Tidak Aktif'?'selected':''; ?>>Tidak Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="mt-3">
                    <?php if ($editData): ?>
                        <button type="submit" name="update" class="btn btn-warning"><i class="bi bi-save"></i> Update</button>
                        <a href="data_santri.php" class="btn btn-secondary">Batal</a>
                    <?php else: ?>
                        <button type="submit" name="tambah" class="btn btn-success"><i class="bi bi-plus-circle"></i> Simpan</button>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Data Santri -->
    <div class="card mt-4 shadow-sm">
        <div class="card-body">
            <h5 class="mb-3 fw-bold text-success"><i class="bi bi-list-check me-2"></i>Daftar Santri</h5>
            <div class="table-responsive">
                <table class="table table-bordered align-middle table-hover">
                    <thead class="table-success text-center">
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>JK</th>
                            <th>Tgl Lahir</th>
                            <th>Wali</th>
                            <th>Kontak</th>
                            <th>Total Poin</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $no=1; while($row = mysqli_fetch_assoc($santri)): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $row['nis']; ?></td>
                            <td><?= $row['nama_santri']; ?></td>
                            <td>Kelas <?= $row['id_kelas']; ?></td>
                            <td><?= $row['jenis_kelamin']; ?></td>
                            <td><?= $row['tanggal_lahir']; ?></td>
                            <td><?= $row['wali']; ?></td>
                            <td><?= $row['kontak_wali']; ?></td>
                            <td class="text-center fw-bold"><?= $row['total_poin']; ?></td>
                            <td>
                                <span class="badge bg-<?= $row['status']=='Aktif'?'success':'secondary'; ?>">
                                    <?= $row['status']; ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="?edit=<?= $row['id_santri']; ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                <a href="?hapus=<?= $row['id_santri']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus data ini?')"><i class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
