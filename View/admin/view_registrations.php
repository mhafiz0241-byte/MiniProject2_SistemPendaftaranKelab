<?php
session_start();
require_once '../../config/conn.php';

// Semak keselamatan: pastikan hanya admin yang boleh akses
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php?error=" . urlencode("Akses ditolak! Sila log masuk sebagai admin."));
    exit();
}

// Query diselaraskan menggunakan r.user_id mengikut struktur database anda
$query = "SELECT r.id, u.username AS student_name, c.club_name, r.registration_date 
          FROM registrations r
          JOIN users u ON r.user_id = u.id
          JOIN clubs c ON r.club_id = c.id
          ORDER BY r.id DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senarai Pendaftaran Pelajar - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Navigasi Admin -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">Admin Panel - Sistem Kelab</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="manage_clubs.php">Urus Kelab</a>
                <a class="nav-link active" href="view_registrations.php">Senarai Pendaftaran Pelajar</a>
                <a class="nav-link text-danger" href="../auth/logout.php">Log Keluar</a>
            </div>
        </div>
    </nav>

    <!-- Kandungan Utama -->
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Senarai Pendaftaran Pelajar</h2>
            <a href="dashboard.php" class="btn btn-secondary btn-sm">Kembali ke Dashboard</a>
        </div>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
        <?php endif; ?>

        <div class="card shadow p-3">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>No.</th>
                            <th>Nama Pelajar</th>
                            <th>Kelab Disertai</th>
                            <th>Tarikh Pendaftaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                                    <td>
                                        <span class="badge bg-primary">
                                            <?php echo htmlspecialchars($row['club_name']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['registration_date'] ?? 'Tiada Tarikh'); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Tiada rekod pendaftaran pelajar buat masa ini.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>