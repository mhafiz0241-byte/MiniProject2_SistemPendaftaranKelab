<?php
session_start();
require_once '../../config/conn.php';

// Pastikan hanya admin yang boleh akses
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php?error=" . urlencode("Akses ditolak! Sila log masuk sebagai admin."));
    exit();
}

// Semak permintaan AJAX
if (isset($_GET['ajax_search'])) {
    $search = trim($_GET['ajax_search']);
    $sql = "SELECT r.*, u.username AS student_name, c.club_name 
            FROM registrations r 
            JOIN users u ON r.user_id = u.id 
            JOIN clubs c ON r.club_id = c.id";

    if ($search !== '') {
        $sql .= " WHERE u.username LIKE ? OR c.club_name LIKE ? ORDER BY r.registration_date DESC";
        $stmt = $conn->prepare($sql);
        $searchTerm = "%" . $search . "%";
        $stmt->bind_param("ss", $searchTerm, $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $sql .= " ORDER BY r.registration_date DESC";
        $result = $conn->query($sql);
    }

    if ($result && $result->num_rows > 0) {
        $no = 1;
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $no++ . '</td>';
            echo '<td class="fw-bold">' . htmlspecialchars($row['student_name']) . '</td>';
            echo '<td><span class="badge bg-primary">' . htmlspecialchars($row['club_name']) . '</span></td>';
            echo '<td>' . $row['registration_date'] . '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="4" class="text-center text-danger py-4">Tiada rekod pendaftaran dijumpai.</td></tr>';
    }
    exit();
}

// Paparan asal halaman
$query = "SELECT r.*, u.username AS student_name, c.club_name 
          FROM registrations r 
          JOIN users u ON r.user_id = u.id 
          JOIN clubs c ON r.club_id = c.id 
          ORDER BY r.registration_date DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senarai Pendaftaran Pelajar - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

    <!-- Navbar Admin -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">Admin Panel - Sistem Kelab</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="manage_clubs.php">Urus Kelab</a>
                <a class="nav-link active" href="view_registrations.php">Senarai Pendaftaran Pelajar</a>
                <a class="nav-link text-warning" href="../auth/logout.php">Log Keluar</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5 mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Senarai Pendaftaran Pelajar</h2>
            <a href="dashboard.php" class="btn btn-secondary">Kembali ke Dashboard</a>
        </div>

        <div class="card shadow border-0 rounded-4 p-4 bg-white">
            
            <!-- Kotak Carian -->
            <div class="mb-4">
                <label for="searchStudent" class="form-label fw-bold text-secondary">
                    <i class="fa-solid fa-magnifying-glass"></i> Cari Pelajar atau Kelab (Live Search):
                </label>
                <input type="text" id="searchStudent" class="form-control form-control-lg" placeholder="Taip nama pelajar atau nama kelab...">
            </div>

            <!-- Jadual Paparan Data -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>No.</th>
                            <th>Nama Pelajar</th>
                            <th>Kelab Disertai</th>
                            <th>Tarikh Pendaftaran</th>
                        </tr>
                    </thead>
                    <tbody id="registrationTableBody">
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td class="fw-bold"><?php echo htmlspecialchars($row['student_name']); ?></td>
                                    <td><span class="badge bg-primary"><?php echo htmlspecialchars($row['club_name']); ?></span></td>
                                    <td><?php echo $row['registration_date']; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center text-danger py-4">Tiada rekod pendaftaran dijumpai.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- Skrip Fetch API Moden -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    const searchInput = document.getElementById('searchStudent');
    const tableBody = document.getElementById('registrationTableBody');

    searchInput.addEventListener('keyup', function() {
        let query = this.value.trim();

        // Menggunakan Fetch API untuk hantar permintaan ke fail ini sendiri
        fetch('view_registrations.php?ajax_search=' + encodeURIComponent(query))
            .then(response => response.text())
            .then(data => {
                tableBody.innerHTML = data;
            })
            .catch(error => {
                console.error('Ralat AJAX:', error);
            });
    });
    </script>
</body>
</html>