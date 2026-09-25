<?php
session_start();

// Naik dua tingkat dari View/student/ untuk sampai ke root, kemudian masuk ke config/conn.php
require_once '../../config/conn.php';

// Semak sama ada pengguna sudah log masuk dan berperanan sebagai student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: ../auth/login.php");
    exit();
}

// Panggil header dari View/layout/header.php (Naik satu tingkat ke View/ kemudian masuk layout/)
include '../layout/header.php';
?>

<div class="container mt-4">
    <div class="p-5 mb-4 bg-white rounded-3 shadow-sm border">
        <div class="container-fluid py-3">
            <h1 class="display-6 fw-bold">Selamat Datang, <?= htmlspecialchars($_SESSION['username']); ?>!</h1>
            <p class="col-md-8 fs-5 text-muted">Portal Pelajar - Sistem Pendaftaran Kelab</p>
            <hr class="my-4">
            
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-success">Senarai Kelab</h5>
                            <p class="card-text">Lihat dan daftar kelab pilihan anda secara dalam talian.</p>
                            <a href="register_club.php" class="btn btn-success">Lihat Kelab</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-danger">Akaun Saya</h5>
                            <p class="card-text">Log keluar daripada sistem secara selamat.</p>
                            <a href="../auth/logout.php" class="btn btn-outline-danger">Log Keluar</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php 
// Panggil footer dari View/layout/footer.php
include '../layout/footer.php'; 
?>