<?php
session_start();
include 'conn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit();
}

include 'header.php';
?>

<div class="container mt-4">
    <div class="p-4 mb-4 bg-light rounded-3 shadow-sm">
        <h2>Selamat Datang, <?= htmlspecialchars($_SESSION['username']); ?>!</h2>
        <p class="mb-0">Portal Pelajar - Sistem Pendaftaran Kelab</p>
    </div>

    <div class="row mt-4">
        <div class="col-md-6 mb-3">
            <div class="card text-center h-100 shadow-sm">
                <div class="card-body d-flex flex-column justify-content-center">
                    <h5 class="card-title">Senarai Kelab</h5>
                    <p class="card-text">Lihat dan daftar kelab pilihan anda secara dalam talian.</p>
                    <div>
                        <a href="club_list.php" class="btn btn-primary">Lihat Kelab</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="card text-center h-100 shadow-sm">
                <div class="card-body d-flex flex-column justify-content-center">
                    <h5 class="card-title">Akaun Saya</h5>
                    <p class="card-text">Log keluar daripada sistem secara selamat.</p>
                    <div>
                        <a href="logout.php" class="btn btn-danger">Log Keluar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>