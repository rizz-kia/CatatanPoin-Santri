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
    $nama_mapel = $_POST['nama_mapel'];
    $guru_pengampu = $_POST['guru_pengampu'];
    $keterangan = $_POST['keterangan'];
    $kategori = $_POST['kategori'];

    $query = "INSERT INTO mata_pelajaran (nama_mapel, guru_pengampu, keterangan, kategori) 
              VALUES ('$nama_mapel', '$guru_pengampu', '$keterangan', '$kategori')";
    mysqli_query($koneksi, $query);
    header("Location: data_mapel.php");
    exit;
}

// --- Hapus Data ---
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM mata_pelajaran WHERE id_mapel='$id'");
    header("Location: data_mapel.php");
    exit;
}

// --- Update Data ---
if (isset($_POST['update'])) {
    $id_mapel = $_POST['id_mapel'];
    $nama_mapel = $_POST['nama_mapel'];
    $guru_pengampu = $_POST['guru_pengampu'];
    $keterangan = $_POST['keterangan'];
    $kategori = $_POST['kategori'];

    mysqli_query($koneksi, "UPDATE mata_pelajaran SET 
        nama_mapel='$nama_mapel',
        guru_pengampu='$guru_pengampu',
        keterangan='$keterangan',
        kategori='$kategori'
        WHERE id_mapel='$id_mapel'");
    header("Location: data_mapel.php");
    exit;
}

// --- Ambil Data ---
$mapel = mysqli_query($koneksi, "SELECT * FROM mata_pelajaran ORDER BY id_mapel DESC");

// --- Edit Data ---
$editData = null;
if (isset($_GET['edit'])) {
    $id_edit = $_GET['edit'];
    $editData = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM mata_pelajaran WHERE id_mapel='$id_edit'"));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Data Mata Pelajaran | Catatan Poin Santri</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-success">
  <div class="container">
    <a class="navbar-brand fw-bold" href="#">Catatan Poin Santri</a>
    <div class="d-flex">
      <a href="../admin/dashboard.php" class="btn btn-light btn-sm me-2">Dashboard</a>
      <a href="../logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>

<!-- Konten Utama -->
<div class="container py-4">
    <h2 class="text-success mb-4 fw-bold"><i class="bi bi-book-fill me-2"></i>Data Mata Pelajaran</h2>

    <!-- Tombol Tambah/Edit -->
    <button class="btn btn-success mb-3" data-bs-toggle="collapse" data-bs-target="#formMapel">
        <i class="bi bi-plus-circle me-1"></i><?= $editData ? 'Edit Mata Pelajaran' : 'Tambah Mata Pelajaran' ?>
    </button>

    <!-- Tombol Kembali -->
    <a href="../admin/dashboard.php" class="btn btn-secondary mb-3">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>

    <!-- Form Tambah/Edit -->
    <div id="formMapel" class="collapse show">
        <div class="card card-body shadow-sm">
            <form method="POST">
                <?php if ($editData): ?>
                    <input type="hidden" name="id_mapel" value="<?= $editData['id_mapel']; ?>">
                <?php endif; ?>

                <div class="row g-3">
                    <!-- Nama Mapel -->
                    <div class="col-md-3">
                        <label>Nama Mapel</label>
                        <select name="nama_mapel" class="form-select" required>
                            <option value="">-- Pilih Mata Pelajaran --</option>

                            <optgroup label="Sekolah">
                                <?php 
                                $mapelSekolah = [
                                    "Bahasa Indonesia", "Matematika", "Bahasa Inggris", "PKN", 
                                    "Ilmu Pengetahuan Alam", "Ilmu Pengetahuan Sosial", "Sosiologi", 
                                    "Geografi", "Ilmu Ekonomi", "Sejarah Indonesia", "Informatika", 
                                    "PJOK", "PKWU", "Seni Budaya", "Baca Tulis Al-Quran", 
                                    "Ilmu Fiqih", "Aqidah Akhlaq", "Al-Quran Hadits", 
                                    "Sejarah Kebudayaan Islam", "Bahasa Arab"
                                ];
                                foreach ($mapelSekolah as $m) {
                                    $selected = (isset($editData) && $editData['nama_mapel'] == $m) ? 'selected' : '';
                                    echo "<option value='$m' $selected>$m</option>";
                                }
                                ?>
                            </optgroup>

                            <optgroup label="Pesantren">
                                <?php 
                                $mapelPesantren = [
                                    "Tahsinul Quran", "Tahfizhul Quran", 
                                    "Ilqa Mufradat (Vocabulary)", "Muhadatsah (Conversation)", 
                                    "Kitab (Aqidah, Fiqih, Akhlaq)", "Nahwu dan Sharaf"
                                ];
                                foreach ($mapelPesantren as $m) {
                                    $selected = (isset($editData) && $editData['nama_mapel'] == $m) ? 'selected' : '';
                                    echo "<option value='$m' $selected>$m</option>";
                                }
                                ?>
                            </optgroup>
                        </select>
                    </div>

                    <!-- Guru Pengampu -->
                    <div class="col-md-3">
                        <label>Guru Pengampu</label>
                        <input type="text" name="guru_pengampu" class="form-control" 
                               value="<?= $editData['guru_pengampu'] ?? ''; ?>" required>
                    </div>

                    <!-- Kategori -->
                    <div class="col-md-3">
                        <label>Kategori</label>
                        <select name="kategori" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php 
                            $kategoriList = ['Sekolah', 'Pesantren'];
                            foreach ($kategoriList as $kategori) {
                                $selected = (isset($editData) && $editData['kategori'] == $kategori) ? 'selected' : '';
                                echo "<option value='$kategori' $selected>$kategori</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Keterangan -->
                    <div class="col-md-3">
                        <label>Keterangan</label>
                        <input type="text" name="keterangan" class="form-control" 
                               value="<?= $editData['keterangan'] ?? ''; ?>">
                    </div>
                </div>

                <div class="mt-3">
                    <?php if ($editData): ?>
                        <button type="submit" name="update" class="btn btn-warning">
                            <i class="bi bi-save"></i> Update
                        </button>
                        <a href="data_mapel.php" class="btn btn-secondary">Batal</a>
                    <?php else: ?>
                        <button type="submit" name="tambah" class="btn btn-success">
                            <i class="bi bi-plus-circle"></i> Simpan
                        </button>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Data -->
    <div class="card mt-4 shadow-sm">
        <div class="card-body">
            <h5 class="mb-3 fw-bold text-success"><i class="bi bi-journal-text me-2"></i>Daftar Mata Pelajaran</h5>
            <div class="table-responsive">
                <table class="table table-bordered align-middle table-hover text-center">
                    <thead class="table-success">
                        <tr>
                            <th>No</th>
                            <th>Nama Mapel</th>
                            <th>Guru Pengampu</th>
                            <th>Kategori</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $no=1; while($row = mysqli_fetch_assoc($mapel)): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($row['nama_mapel']); ?></td>
                            <td><?= htmlspecialchars($row['guru_pengampu']); ?></td>
                            <td><?= htmlspecialchars($row['kategori']); ?></td>
                            <td><?= htmlspecialchars($row['keterangan']); ?></td>
                            <td>
                                <a href="?edit=<?= $row['id_mapel']; ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                <a href="?hapus=<?= $row['id_mapel']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus data ini?')"><i class="bi bi-trash"></i></a>
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
