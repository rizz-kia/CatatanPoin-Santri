<?php
include "../koneksi.php";
session_start();

// Cek login
if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

// --- Tambah Data ---
if (isset($_POST['tambah'])) {
    $nama_kelas = $_POST['nama_kelas'];
    $wali_kelas = $_POST['wali_kelas'];
    $tahun_ajaran = $_POST['tahun_ajaran'];

    $query = "INSERT INTO kelas (nama_kelas, wali_kelas, tahun_ajaran) 
              VALUES ('$nama_kelas', '$wali_kelas', '$tahun_ajaran')";
    if (!mysqli_query($koneksi, $query)) {
        die("Error tambah: " . mysqli_error($koneksi));
    }
    header("Location: data_kelas.php");
    exit;
}

// --- Hapus Data ---
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM kelas WHERE id_kelas='$id'");
    header("Location: data_kelas.php");
    exit;
}

// --- Edit Data ---
if (isset($_POST['update'])) {
    $id = $_POST['id_kelas'];
    $nama_kelas = $_POST['nama_kelas'];
    $wali_kelas = $_POST['wali_kelas'];
    $tahun_ajaran = $_POST['tahun_ajaran'];

    $update = "UPDATE kelas SET 
                nama_kelas='$nama_kelas',
                wali_kelas='$wali_kelas',
                tahun_ajaran='$tahun_ajaran'
               WHERE id_kelas='$id'";
    if (!mysqli_query($koneksi, $update)) {
        die("Error update: " . mysqli_error($koneksi));
    }
    header("Location: data_kelas.php");
    exit;
}

// Ambil semua data kelas
$kelas = mysqli_query($koneksi, "SELECT * FROM kelas ORDER BY id_kelas DESC");

// Jika sedang edit
$editData = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $editData = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM kelas WHERE id_kelas='$id'"));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Data Kelas | Catatan Poin Santri</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-success">
  <div class="container">
    <a class="navbar-brand fw-bold" href="#">Catatan Poin Santri</a>
    <div class="d-flex">
      <a href="../admin/dashboard.php" class="btn btn-light btn-sm me-2">Dashboard</a>
      <a href="../logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>

<div class="container py-4">
    <h2 class="text-success mb-4 fw-bold"><i class="bi bi-building-fill me-2"></i>Data Kelas</h2>

    <!-- Tombol Tambah/Edit -->
    <button class="btn btn-success mb-3" data-bs-toggle="collapse" data-bs-target="#formKelas">
        <i class="bi bi-plus-circle me-1"></i><?= $editData ? 'Edit Kelas' : 'Tambah Kelas' ?>
    </button>

    <a href="../admin/dashboard.php" class="btn btn-secondary mb-3">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>

    <!-- Form Tambah/Edit -->
    <div id="formKelas" class="collapse show">
        <div class="card card-body shadow-sm">
            <form method="post">
                <?php if ($editData): ?>
                    <input type="hidden" name="id_kelas" value="<?= $editData['id_kelas']; ?>">
                <?php endif; ?>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label>Nama Kelas</label>
                        <input type="text" name="nama_kelas" class="form-control" value="<?= $editData['nama_kelas'] ?? ''; ?>" required>
                    </div>

                    <div class="col-md-4">
                        <label>Wali Kelas</label>
                        <input type="text" name="wali_kelas" class="form-control" value="<?= $editData['wali_kelas'] ?? ''; ?>" required>
                    </div>

                    <div class="col-md-4">
                        <label>Tahun Ajaran</label>
                        <select name="tahun_ajaran" class="form-select" required>
                            <option value="">-- Pilih Tahun Ajaran --</option>
                            <?php
                            for ($i = 2020; $i <= 2030; $i++) {
                                $ta = $i . '/' . ($i + 1);
                                $selected = isset($editData) && $editData['tahun_ajaran'] == $ta ? 'selected' : '';
                                echo "<option value='$ta' $selected>$ta</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="mt-3">
                    <?php if ($editData): ?>
                        <button type="submit" name="update" class="btn btn-warning"><i class="bi bi-save"></i> Update</button>
                        <a href="data_kelas.php" class="btn btn-secondary">Batal</a>
                    <?php else: ?>
                        <button type="submit" name="tambah" class="btn btn-success"><i class="bi bi-plus-circle"></i> Simpan</button>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Data Kelas -->
    <div class="card mt-4 shadow-sm">
        <div class="card-body">
            <h5 class="mb-3 fw-bold text-success"><i class="bi bi-list-ul me-2"></i>Daftar Kelas</h5>
            <div class="table-responsive">
                <table class="table table-bordered align-middle table-hover">
                    <thead class="table-success text-center">
                        <tr>
                            <th>No</th>
                            <th>Nama Kelas</th>
                            <th>Wali Kelas</th>
                            <th>Tahun Ajaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $no=1; while($row = mysqli_fetch_assoc($kelas)): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($row['nama_kelas']); ?></td>
                            <td><?= htmlspecialchars($row['wali_kelas']); ?></td>
                            <td><?= $row['tahun_ajaran']; ?></td>
                            <td class="text-center">
                                <a href="?edit=<?= $row['id_kelas']; ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                <a href="?hapus=<?= $row['id_kelas']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus data ini?')"><i class="bi bi-trash"></i></a>
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
