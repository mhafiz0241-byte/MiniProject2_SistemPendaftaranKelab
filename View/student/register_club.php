<?php
session_start();
require_once '../../config/conn.php';

// Pastikan hanya pelajar yang boleh akses
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../auth/login.php?error=" . urlencode("Akses ditolak! Sila log masuk sebagai pelajar."));
    exit();
}

$student_id = $_SESSION['user_id'];

// Semak sama ada pelajar sudah mendaftar kelab atau belum (Guna nama jadual 'registrations')
$checkQuery = "SELECT rc.*, c.club_name, c.description FROM registrations rc 
               JOIN clubs c ON rc.club_id = c.id 
               WHERE rc.user_id = ?";
$stmt = $conn->prepare($checkQuery);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$resultReg = $stmt->get_result();
$isRegistered = $resultReg->num_rows > 0;
$registeredClub = $isRegistered ? $resultReg->fetch_assoc() : null;
$stmt->close();

// Ambil senarai semua kelab untuk dropdown asal
$clubQuery = "SELECT * FROM clubs ORDER BY club_name ASC";
$clubResult = $conn->query($clubQuery);
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Kelab - Pelajar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

    <!-- Navbar Pelajar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container">
            <a class="navbar-brand" href="dashboard_student.php">Sistem Pendaftaran Kelab</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link active" href="register_club.php">Mohon Kelab</a>
                <a class="nav-link text-warning" href="../auth/logout.php">Log Keluar</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                
                <!-- Header kad -->
                <div class="card shadow border-0 rounded-4 p-4 bg-white mb-4">
                    <div class="text-center mb-3">
                        <i class="fa-solid fa-address-card fa-3x text-success mb-2"></i>
                        <h2 class="fw-bold">Borang Permohonan Kelab</h2>
                        <p class="text-muted">Sila pilih atau cari kelab yang ingin anda sertai bagi sesi ini</p>
                    </div>

                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
                    <?php endif; ?>

                    <?php if (isset($_GET['success'])): ?>
                        <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
                    <?php endif; ?>

                    <?php if ($isRegistered): ?>
                        <!-- Paparan Jika Pelajar Sudah Mendaftar Kelab -->
                        <div class="alert alert-info border-0 shadow-sm p-4">
                            <h4 class="alert-heading fw-bold"><i class="fa-solid fa-circle-check"></i> Anda Telah Berdaftar!</h4>
                            <p class="mb-1">Anda sudah menyertai kelab berikut:</p>
                            <hr>
                            <h5 class="fw-bold text-success"><?php echo htmlspecialchars($registeredClub['club_name']); ?></h5>
                            <p class="text-muted mb-0"><?php echo htmlspecialchars($registeredClub['description']); ?></p>
                        </div>
                    <?php else: ?>
                        <!-- Borang Pendaftaran & Carian AJAX -->
                        <form action="../../Controller/MemberController.php?action=register" method="POST">
                            
                            <!-- Bahagian 1: Dropdown Pilihan Asal -->
                            <div class="mb-4">
                                <label for="club_id" class="form-label fw-bold">Pilih Kelab Pilihan Anda:</label>
                                <select class="form-select form-select-lg" id="club_id" name="club_id" required>
                                    <option value="" selected disabled>-- Pilih Kelab Pilihan Anda --</option>
                                    <?php if ($clubResult && $clubResult->num_rows > 0): ?>
                                        <?php while ($club = $clubResult->fetch_assoc()): ?>
                                            <option value="<?php echo $club['id']; ?>">
                                                📌 <?php echo htmlspecialchars($club['club_name']); ?>
                                            </option>
                                        <?php endwhile; ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-success btn-lg fw-bold">Hantar Permohonan Kelab</button>
                            </div>
                        </form>

                        <hr class="my-4">

                        <!-- Bahagian 2: Ciri Live Search AJAX -->
                        <div class="card bg-light border-0 p-3 rounded-3">
                            <div class="mb-3">
                                <label for="searchClub" class="form-label fw-bold text-secondary">
                                    <i class="fa-solid fa-magnifying-glass"></i> Cari Kelab:
                                </label>
                                <input type="text" id="searchClub" class="form-control" placeholder="Taip nama kelab atau penerangan...">
                            </div>

                            <!-- Tempat paparan keputusan carian AJAX secara dinamik -->
                            <div id="clubSearchResults">
                                <!-- Keputusan dari search_clubs.php akan muncul di sini secara automatik -->
                            </div>
                        </div>

                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>

    <!-- Pustaka jQuery untuk AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    $(document).ready(function(){
        // Fungsi Live Search AJAX apabila pengguna menaip
        $('#searchClub').on('keyup', function(){
            var query = $(this).val();
            
            $.ajax({
                url: '../ajax/search_clubs.php',
                method: 'GET',
                data: { q: query },
                success: function(data){
                    $('#clubSearchResults').html(data);
                },
                error: function() {
                    $('#clubSearchResults').html('<p class="text-danger text-center">Gagal memuatkan carian.</p>');
                }
            });
        });

        // Cetuskan carian kosong secara automatik semasa mula buka halaman
        $('#searchClub').trigger('keyup');
    });
    </script>
</body>
</html>