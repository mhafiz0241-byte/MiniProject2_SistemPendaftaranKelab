<?php
session_start();

// Sambungan database (naik dua tingkat dari View/admin/ ke root, masuk config/)
require_once '../../config/conn.php';

// Semak keselamatan sesi pengguna (pastikan admin yang log masuk)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Panggil header layout (naik satu tingkat ke View/, masuk layout/)
include '../layout/header.php';
?>

<div class="container mt-4">
    <div class="p-5 mb-4 bg-white rounded-3 shadow-sm border">
        <div class="container-fluid py-3">
            <!-- Paparkan nama admin secara dinamik daripada session -->
            <h1 class="display-6 fw-bold text-dark">Selamat Datang, <?= htmlspecialchars($_SESSION['username']); ?>!</h1>
            <p class="col-md-8 fs-5 text-muted">Dashboard Admin - Urus kelab, lihat senarai pendaftaran pelajar, dan kawal selia sistem dengan mudah.</p>
            <hr class="my-4">
            
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card h-100 shadow-sm border-0 bg-light">
                        <div class="card-body">
                            <h5 class="card-title text-primary fw-bold">Pengurusan Kelab</h5>
                            <p class="card-text text-secondary">Tambah, kemas kini, atau padam maklumat kelab yang aktif dalam sistem.</p>
                            <a href="manage_clubs.php" class="btn btn-primary">Tambah Kelab Baru</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card h-100 shadow-sm border-0 bg-light">
                        <div class="card-body">
                            <h5 class="card-title text-danger fw-bold">Akaun Admin</h5>
                            <p class="card-text text-secondary">Log keluar daripada sesi pentadbiran dengan selamat.</p>
                            <a href="../auth/logout.php" class="btn btn-outline-danger">Log Keluar</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php 
// Panggil footer layout
include '../layout/footer.php'; 
?>