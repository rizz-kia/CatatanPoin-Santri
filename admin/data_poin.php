<?php  
include "../koneksi.php";
session_start();

// Cek login
if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

// === TAMBAH DATA ===
if (isset($_POST['tambah'])) {
    $nama_kategori = $_POST['nama_kategori'];
    $jenis = $_POST['jenis'];
    $deskripsi = $_POST['deskripsi'];
    $bobot_poin = $_POST['bobot_poin'];

    $query = "INSERT INTO kategori_poin (nama_kategori, jenis, deskripsi, bobot_poin)
              VALUES ('$nama_kategori', '$jenis', '$deskripsi', '$bobot_poin')";
    if (!mysqli_query($koneksi, $query)) {
        die("Query Insert Error: " . mysqli_error($koneksi));
    }
    header("Location: data_catatan_poin.php");
    exit;
}

// === HAPUS DATA ===
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    if (!mysqli_query($koneksi, "DELETE FROM kategori_poin WHERE id_poin='$id'")) {
        die("Query Delete Error: " . mysqli_error($koneksi));
    }
    header("Location: data_catatan_poin.php");
    exit;
}

// === UPDATE DATA ===
if (isset($_POST['update'])) {
    $id_poin = $_POST['id_poin'];
    $nama_kategori = $_POST['nama_kategori'];
    $jenis = $_POST['jenis'];
    $deskripsi = $_POST['deskripsi'];
    $bobot_poin = $_POST['bobot_poin'];

    $query = "UPDATE kategori_poin SET 
                nama_kategori='$nama_kategori',
                jenis='$jenis',
                deskripsi='$deskripsi',
                bobot_poin='$bobot_poin'
              WHERE id_kategori='$id_kategori'";
    if (!mysqli_query($koneksi, $query)) {
        die("Query Update Error: " . mysqli_error($koneksi));
    }
    header("Location: data_catatan_poin.php");
    exit;
}

// === AMBIL DATA ===
$poin = mysqli_query($koneksi, "SELECT * FROM kategori_poin ORDER BY id_kategori DESC");
if (!$poin) {
    die("Query Select Error: " . mysqli_error($koneksi));
}

// === EDIT DATA ===
$editData = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = mysqli_query($koneksi, "SELECT * FROM kategori_poin WHERE id_kategori='$id'");
    if (!$result) {
        die("Query Edit Error: " . mysqli_error($koneksi));
    }
    $editData = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Data Catatan Poin | Catatan Poin Santri</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-success">
  <div class="container">
    <a class="navbar-brand fw-bold" href="#">Catatan Poin Santri</a>
    <div class="d-flex">
      <a href="dashboard.php" class="btn btn-light btn-sm me-2">Dashboard</a>
      <a href="../logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>

<div class="container py-4">
    <h2 class="text-success mb-4 fw-bold"><i class="bi bi-journal-text me-2"></i>Data Kategori Poin</h2>

    <!-- Tombol Tambah/Edit -->
    <button class="btn btn-success mb-3" data-bs-toggle="collapse" data-bs-target="#formPoin">
        <i class="bi bi-plus-circle me-1"></i><?= $editData ? 'Edit Poin' : 'Tambah Poin' ?>
    </button>

    <a href="dashboard.php" class="btn btn-secondary mb-3">
        <i class="bi bi-arrow-left"></i> Kembali 
    </a>

    <!-- Form Tambah/Edit -->
    <div id="formPoin" class="collapse show">
        <div class="card card-body shadow-sm">
            <form method="post">
                <?php if ($editData): ?>
                    <input type="hidden" name="id_kategori" value="<?= $editData['id_kategori']; ?>">
                <?php endif; ?>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label>Kategori</label>
                        <select name="nama_kategori" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php
                            $kategoriList = ['Keamanan', 'Bahasa', 'Ta\'mir/Ta\'lim', 'Kebersihan'];
                            foreach ($kategoriList as $kat) {
                                $selected = isset($editData) && $editData['nama_kategori'] == $kat ? 'selected' : '';
                                echo "<option value='$kat' $selected>$kat</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label>Jenis</label>
                        <input type="text" name="jenis" class="form-control" 
                               value="<?= $editData['jenis'] ?? ''; ?>" required>
                    </div>

                    <div class="col-md-4">
                        <label>Bobot Poin</label>
                        <input type="number" name="bobot_poin" class="form-control"
                               value="<?= $editData['bobot_poin'] ?? ''; ?>" required>
                    </div>

                    <div class="col-md-12">
                        <label>Deskripsi Pelanggaran</label>
                        <textarea name="deskripsi" class="form-control" rows="3" required><?= $editData['deskripsi'] ?? ''; ?></textarea>
                    </div>
                </div>

                <div class="mt-3">
                    <?php if ($editData): ?>
                        <button type="submit" name="update" class="btn btn-warning"><i class="bi bi-save"></i> Update</button>
                        <a href="data_catatan_poin.php" class="btn btn-secondary">Batal</a>
                    <?php else: ?>
                        <button type="submit" name="tambah" class="btn btn-success"><i class="bi bi-plus-circle"></i> Simpan</button>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Data -->
    <div class="card mt-4 shadow-sm">
        <div class="card-body">
            <h5 class="mb-3 fw-bold text-success"><i class="bi bi-list-ul me-2"></i>Daftar Kategori Poin</h5>
            <div class="table-responsive">
                <table class="table table-bordered align-middle table-hover text-center">
                    <thead class="table-success">
                        <tr>
                            <th>No</th>
                            <th>Kategori</th>
                            <th>Jenis</th>
                            <th>Deskripsi</th>
                            <th>Bobot Poin</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    if (mysqli_num_rows($poin) > 0):
                        $no = 1; 
                        while ($row = mysqli_fetch_assoc($poin)): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($row['nama_kategori']); ?></td>
                            <td><?= htmlspecialchars($row['jenis']); ?></td>
                            <td class="text-start"><?= nl2br(htmlspecialchars($row['deskripsi'])); ?></td>
                            <td><?= htmlspecialchars($row['bobot_poin']); ?></td>
                            <td>
                                <a href="?edit=<?= $row['id_kategori']; ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                <a href="?hapus=<?= $row['id_kategori']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus data ini?')"><i class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                    <?php endwhile; 
                    else: ?>
                        <tr><td colspan="6" class="text-center text-muted">Belum ada data</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
