<?php
// Redirect otomatis ke dashboard jika sudah login
session_start();
if (isset($_SESSION['role'])) {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catatan Poin Santri</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #b6f0c0, #f0fff4);
            font-family: 'Poppins', sans-serif;
        }
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            text-align: center;
        }
        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            background-color: #ffffffb5;
            backdrop-filter: blur(10px);
        }
        .btn-success {
            background-color: #198754;
            border: none;
            border-radius: 10px;
        }
        .btn-success:hover {
            background-color: #157347;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #555;
            font-size: 14px;
        }
        h1 span {
            color: #198754;
        }
    </style>
</head>
<body>
    <section class="hero">
        <div class="container">
            <div class="card p-5 mx-auto" style="max-width: 500px;">
                <img src="assets/img/logo.png" alt="Logo Pesantren" class="mx-auto mb-3" width="100">
                <h1 class="fw-bold">Catatan <span>Poin Santri</span></h1>
                <p class="text-muted mb-4">Sistem pencatatan nilai dan poin untuk santri pondok pesantren.<br>Kelola data dengan mudah dan efisien.</p>

                <a href="login.php" class="btn btn-success w-100 py-2">
                    <i class="bi bi-box-arrow-in-right"></i> Masuk ke Sistem
                </a>
            </div>

            <div class="footer mt-4">
                © 2025 Pondok Pesantren | Dibuat oleh <strong>Ranti Amanda Rizkia</strong>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
