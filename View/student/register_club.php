<?php
session_start();
require_once '../../config/conn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../auth/login.php?error=" . urlencode("Sila log masuk terlebih dahulu."));
    exit();
}

$query = "SELECT * FROM clubs";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kelab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">Sistem Pendaftaran Kelab</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="my_registrations.php">Kelab Saya</a>
                <a class="nav-link text-danger" href="../auth/logout.php">Log Keluar</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow p-4">
                    <h3 class="text-center mb-4">Daftar Kelab Baru</h3>

                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
                    <?php endif; ?>

                    <?php if (isset($_GET['success'])): ?>
                        <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
                    <?php endif; ?>

                    <form action="../../controllers/MemberController.php?action=register_club" method="POST">
                        <div class="mb-3">
                            <label for="club_id" class="form-label">Pilih Kelab:</label>
                            <select name="club_id" id="club_id" class="form-select" required>
                                <option value="">-- Sila Pilih Kelab --</option>
                                <?php while ($club = $result->fetch_assoc()): ?>
                                    <option value="<?php echo $club['id']; ?>">
                                        <?php echo htmlspecialchars($club['club_name']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Daftar Sekarang</button>
                            <a href="dashboard.php" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>