<?php
session_start();
require_once '../../config/conn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php?error=" . urlencode("Akses ditolak!"));
    exit();
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kelab - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow p-4">
                    <h3 class="mb-4 text-center">Tambah Kelab Baru</h3>

                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
                    <?php endif; ?>

                    <form action="../../Controller/ClubController.php?action=add" method="POST">
                        <div class="mb-3">
                            <label for="club_name" class="form-label">Nama Kelab</label>
                            <input type="text" class="form-control" id="club_name" name="club_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Penerangan</label>
                            <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="manage_clubs.php" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Simpan Kelab</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>