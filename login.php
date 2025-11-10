<?php
session_start();
include "koneksi.php";

$error = "";

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = md5($_POST['password']);

    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username' AND password='$password' AND status='aktif'");
    $cek = mysqli_num_rows($query);

    if ($cek > 0) {
        $data = mysqli_fetch_assoc($query);
        
        // Set session
        $_SESSION['id_user'] = $data['id_user'];
        $_SESSION['nama_user'] = $data['nama_user'];
        $_SESSION['role'] = $data['role'];

        // Arahkan berdasarkan role
        if ($data['role'] == 'admin') {
            header("Location: admin/dashboard.php");
        } elseif ($data['role'] == 'guru') {
            header("Location: guru/dashboard.php");
        } elseif ($data['role'] == 'santri') {
            header("Location: santri/dashboard.php");
        } else {
            $error = "Role tidak dikenal!";
        }
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Catatan Poin Santri</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #b6f0c0, #f0fff4);
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            width: 400px;
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center" style="min-height: 100vh;">

    <div class="card p-4">
        <h3 class="text-center text-success fw-bold mb-3">Catatan Poin Santri</h3>
        <p class="text-center text-muted mb-4">Masukkan akun Anda untuk melanjutkan</p>

        <?php if ($error != ""): ?>
            <div class="alert alert-danger text-center py-2"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required>
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword"><i class="bi bi-eye"></i></button>
                </div>
            </div>

            <button type="submit" name="login" class="btn btn-success w-100">Masuk</button>
        </form>

        <p class="text-center mt-4 text-muted small">© 2025 Pondok Pesantren | Tim Pondok</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Tampilkan/Sembunyikan password
        document.getElementById('togglePassword').addEventListener('click', function () {
            const passField = document.getElementById('password');
            const icon = this.querySelector('i');
            const type = passField.getAttribute('type') === 'password' ? 'text' : 'password';
            passField.setAttribute('type', type);
            icon.classList.toggle('bi-eye');
            icon.classList.toggle('bi-eye-slash');
        });
    </script>

</body>
</html>
