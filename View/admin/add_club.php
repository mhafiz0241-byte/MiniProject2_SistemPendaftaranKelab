<?php
session_start();
require_once '../../config/conn.php';

// Semak keselamatan: pastikan hanya admin yang boleh akses
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
    <title>Tambah Kelab Baru - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Navigasi Admin -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">Admin Panel - Sistem Kelab</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="manage_club.php">Urus Kelab</a>
                <a class="nav-link" href="view_registrations.php">Senarai Pendaftaran Pelajar</a>
                <a class="nav-link text-danger" href="../auth/logout.php">Log Keluar</a>
            </div>
        </div>
    </nav>

    <!-- Kandungan Utama -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
                <?php endif; ?>

                <div class="card shadow">
                    <div class="card-header bg-dark text-white">
                        <h4 class="mb-0">Tambah Kelab Baru</h4>
                    </div>
                    <div class="card-body p-4">
                        <!-- Borang menghantar data ke Controller (Pastikan laluan Controller anda tepat) -->
                        <form action="../../Controller/ClubController.php?action=add" method="POST">
                            
                            <div class="mb-3">
                                <label for="club_name" class="form-label">Nama Kelab</label>
                                <input type="text" class="form-control" id="club_name" name="club_name" required placeholder="Cth: Kelab Komputer">
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Penerangan</label>
                                <textarea class="form-control" id="description" name="description" rows="4" required placeholder="Nyatakan ringkasan aktiviti atau matlamat kelab..."></textarea>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="manage_club.php" class="btn btn-secondary">Batal</a>
                                <button type="submit" class="btn btn-success">Simpan Kelab</button>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>