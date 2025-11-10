<?php
include "../koneksi.php";
session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

// --- Tambah Data Guru ---
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama_guru'];
    $nip = $_POST['nip'];
    $mapel = $_POST['mata_pelajaran'];

    $query = "INSERT INTO guru (nama_guru, nip, mata_pelajaran)
              VALUES ('$nama', '$nip', '$mapel')";
    mysqli_query($koneksi, $query);
    header("Location: data_guru.php");
    exit;
}

// --- Hapus Data Guru ---
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM guru WHERE id_guru='$id'");
    header("Location: data_guru.php");
    exit;
}

// --- Edit Data Guru ---
if (isset($_POST['update'])) {
    $id = $_POST['id_guru'];
    $nama = $_POST['nama_guru'];
    $nip = $_POST['nip'];
    $mapel = $_POST['mata_pelajaran'];

    $update = "UPDATE guru SET 
                nama_guru='$nama', nip='$nip', mata_pelajaran='$mapel'
               WHERE id_guru='$id'";
    mysqli_query($koneksi, $update);
    header("Location: data_guru.php");
    exit;
}

// Ambil semua data guru
$guru = mysqli_query($koneksi, "SELECT * FROM guru ORDER BY id_guru DESC");

// Jika sedang edit
$editData = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $editData = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM guru WHERE id_guru='$id'"));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Data Guru</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">
    <h2 class="text-primary mb-4 fw-bold"><i class="bi bi-person-badge-fill me-2"></i>Data Guru</h2>

    <!-- Tombol Tambah -->
    <button class="btn btn-primary mb-3" data-bs-toggle="collapse" data-bs-target="#formGuru">
        <i class="bi bi-plus-circle me-1"></i><?= $editData ? 'Edit Guru' : 'Tambah Guru' ?>
    </button>
    <a href="../admin/dashboard.php" class="btn btn-secondary mb-3"><i class="bi bi-arrow-left"></i> Kembali</a>

    <!-- Form Tambah/Edit Guru -->
    <div id="formGuru" class="collapse show">
        <div class="card card-body shadow-sm">
            <form method="post">
                <?php if ($editData): ?>
                    <input type="hidden" name="id_guru" value="<?= $editData['id_guru']; ?>">
                <?php endif; ?>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label>Nama Guru</label>
                        <input type="text" name="nama_guru" class="form-control" value="<?= $editData['nama_guru'] ?? ''; ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label>NIP</label>
                        <input type="text" name="nip" class="form-control" value="<?= $editData['nip'] ?? ''; ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label>Mata Pelajaran</label>
                        <input type="text" name="mata_pelajaran" class="form-control" value="<?= $editData['mata_pelajaran'] ?? ''; ?>" required>
                    </div>
                </div>

                <div class="mt-3">
                    <?php if ($editData): ?>
                        <button type="submit" name="update" class="btn btn-warning"><i class="bi bi-save"></i> Update</button>
                        <a href="data_guru.php" class="btn btn-secondary">Batal</a>
                    <?php else: ?>
                        <button type="submit" name="tambah" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Simpan</button>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Data Guru -->
    <div class="card mt-4 shadow-sm">
        <div class="card-body">
            <h5 class="mb-3 fw-bold text-primary"><i class="bi bi-list-check me-2"></i>Daftar Guru</h5>
            <div class="table-responsive">
                <table class="table table-bordered align-middle table-hover">
                    <thead class="table-primary text-center">
                        <tr>
                            <th>No</th>
                            <th>Nama Guru</th>
                            <th>NIP</th>
                            <th>Mata Pelajaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $no=1; while($row = mysqli_fetch_assoc($guru)): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $row['nama_guru']; ?></td>
                            <td><?= $row['nip']; ?></td>
                            <td><?= $row['mata_pelajaran']; ?></td>
                            <td class="text-center">
                                <a href="?edit=<?= $row['id_guru']; ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                <a href="?hapus=<?= $row['id_guru']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus data guru ini?')"><i class="bi bi-trash"></i></a>
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
