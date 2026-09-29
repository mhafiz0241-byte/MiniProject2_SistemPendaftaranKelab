<?php
session_start();
require_once '../../config/conn.php';

// Pastikan hanya pelajar yang boleh akses
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../auth/login.php?error=" . urlencode("Akses ditolak! Sila log masuk sebagai pelajar."));
    exit();
}

$student_id = $_SESSION['user_id'];
$username = $_SESSION['username'] ?? 'Pelajar';

// Semak status pendaftaran kelab pelajar
$query = "SELECT r.*, c.club_name, c.description FROM registrations r 
          JOIN clubs c ON r.club_id = c.id 
          WHERE r.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$isRegistered = $result->num_rows > 0;
$myClub = $isRegistered ? $result->fetch_assoc() : null;
$stmt->close();
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pelajar - Sistem Kelab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

    <!-- Navbar Pelajar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container">
            <a class="navbar-brand" href="dashboard_student.php">Panel Pelajar</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link active" href="dashboard_student.php">Dashboard</a>
                <a class="nav-link" href="register_club.php">Mohon Kelab</a>
                <a class="nav-link text-warning" href="../auth/logout.php">Log Keluar</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="p-5 mb-4 bg-white rounded-4 shadow-sm border">
            <div class="container-fluid py-2">
                <h1 class="display-6 fw-bold text-success">Selamat Datang, <?php echo htmlspecialchars($username); ?>!</h1>
                <p class="col-md-8 fs-5 text-muted">Portal rasmi pendaftaran dan semakan status kelab pelajar.</p>
                <hr class="my-4">

                <?php if ($isRegistered): ?>
                    <div class="alert alert-success border-0 shadow-sm p-4">
                        <h4 class="alert-heading fw-bold"><i class="fa-solid fa-circle-check"></i> Status: Telah Berdaftar</h4>
                        <p class="mb-1">Anda telah mendaftar ke dalam kelab berikut:</p>
                        <hr>
                        <h3 class="text-dark fw-bold"><?php echo htmlspecialchars($myClub['club_name']); ?></h3>
                        <p class="text-secondary"><?php echo htmlspecialchars($myClub['description']); ?></p>
                        <small class="text-muted">Tarikh Daftar: <?php echo $myClub['registration_date'] ?? 'N/A'; ?></small>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning border-0 shadow-sm p-4">
                        <h4 class="alert-heading fw-bold"><i class="fa-solid fa-triangle-exclamation"></i> Belum Mendaftar Kelab</h4>
                        <p class="mb-3">Anda masih belum menyertai mana-mana kelab dalam sesi ini. Sila buat permohonan sekarang.</p>
                        <a href="register_club.php" class="btn btn-success fw-bold">Pilih Kelab Sekarang</a>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>