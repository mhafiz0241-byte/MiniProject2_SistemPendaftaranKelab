<?php
session_start();
require_once '../../config/conn.php';

// Pastikan hanya admin yang boleh akses
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php?error=" . urlencode("Akses ditolak! Sila log masuk sebagai admin."));
    exit();
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Sistem Pendaftaran Kelab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

    <!-- Navbar Admin -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">Portal Admin - Sistem Kelab</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="manage_clubs.php">Urus Kelab</a>
                <a class="nav-link" href="view_registrations.php">Senarai Pendaftaran</a>
                <a class="nav-link text-warning" href="../auth/logout.php">Log Keluar</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5 mb-5">
        <div class="card shadow border-0 rounded-4 p-5 bg-white">
            <h2 class="fw-bold mb-3">Selamat Datang, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?>!</h2>
            <p class="text-secondary mb-4">Dashboard Admin - Urus kelab, lihat senarai pendaftaran pelajar, dan kawal selia sistem dengan mudah.</p>
            
            <hr class="mb-4">

            <div class="row g-4">
                <!-- Kad 1: Urus Kelab -->
                <div class="col-md-6">
                    <div class="p-4 border rounded-4 bg-light h-100 d-flex flex-column justify-content-between">
                        <div>
                            <h4 class="fw-bold text-dark"><i class="fa-solid fa-people-roof text-primary"></i> Pengurusan Kelab</h4>
                            <p class="text-secondary mt-2">Tambah, kemas kini, atau padam maklumat kelab yang aktif dalam sistem.</p>
                        </div>
                        <div class="mt-3">
                            <a href="manage_clubs.php" class="btn btn-primary px-4">Urus Kelab</a>
                        </div>
                    </div>
                </div>

                <!-- Kad 2: Senarai Pendaftaran Pelajar (DIAMBIL KIRA & DIKEMBALIKAN DI SINI) -->
                <div class="col-md-6">
                    <div class="p-4 border rounded-4 bg-light h-100 d-flex flex-column justify-content-between">
                        <div>
                            <h4 class="fw-bold text-dark"><i class="fa-solid fa-clipboard-list text-success"></i> Senarai Pendaftaran</h4>
                            <p class="text-secondary mt-2">Semak senarai pelajar yang telah mendaftar ke dalam kelab berserta fungsi carian langsung.</p>
                        </div>
                        <div class="mt-3">
                            <a href="view_registrations.php" class="btn btn-success px-4">Lihat Pendaftaran</a>
                        </div>
                    </div>
                </div>

                <!-- Kad 3: Log Keluar -->
                <div class="col-md-12 mt-3">
                    <div class="p-4 border rounded-4 bg-light d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold text-danger mb-1"><i class="fa-solid fa-right-from-bracket"></i> Akaun Admin</h5>
                            <p class="text-secondary mb-0">Log keluar daripada sesi pentadbiran dengan selamat.</p>
                        </div>
                        <a href="../auth/logout.php" class="btn btn-outline-danger px-4">Log Keluar</a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>