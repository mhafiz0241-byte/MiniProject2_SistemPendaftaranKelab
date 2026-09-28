<?php
session_start();

// Semak sesi pengguna (mesti login & peranan student)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../auth/user.php");
    exit();
}

require_once '../../config/conn.php';

$message = '';
$alertType = 'danger';

// Proses borang pendaftaran kelab
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $clubId = isset($_POST['club_id']) ? intval($_POST['club_id']) : 0;

    if ($clubId <= 0) {
        $message = "Sila pilih kelab yang sah.";
    } else {
        // Semak jika pelajar sudah mendaftar kelab ini (registrations table)
        $checkStmt = $pdo->prepare("SELECT id FROM registrations WHERE user_id = ? AND club_id = ?");
        $checkStmt->execute([$userId, $clubId]);

        if ($checkStmt->rowCount() > 0) {
            $message = "Anda sudah mendaftar untuk kelab ini!";
            $alertType = "warning";
        } else {
            // Masukkan data ke dalam jadual registrations
            $stmt = $pdo->prepare("INSERT INTO registrations (user_id, club_id) VALUES (?, ?)");
            if ($stmt->execute([$userId, $clubId])) {
                $message = "Permohonan pendaftaran kelab berjaya dihantar!";
                $alertType = "success";
            } else {
                $message = "Gagal mendaftar kelab. Sila cuba lagi.";
            }
        }
    }
}

// Ambil senarai kelab dari jadual clubs
$clubsQuery = $pdo->query("SELECT * FROM clubs ORDER BY club_name ASC");
$clubs = $clubsQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Kelab - Portal Pelajar</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Google Fonts (Plus Jakarta Sans) -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f0f4f8;
            color: #2d3748;
            min-height: 100vh;
        }

        /* Custom Modern Navbar */
        .custom-navbar {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            border-bottom: 3px solid #10b981;
        }

        /* Unique Form Card Styling */
        .form-card {
            border: none;
            border-radius: 20px;
            background: #ffffff;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
            overflow: hidden;
            transition: transform 0.2s ease;
        }

        .form-header-custom {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            color: white;
            padding: 28px 30px;
            border: none;
        }

        /* Styled Dropdown & Inputs */
        .form-select-custom {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 16px;
            font-weight: 500;
            color: #334155;
            transition: all 0.2s ease;
        }

        .form-select-custom:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15);
        }

        /* Custom Buttons */
        .btn-emerald {
            background: #10b981;
            color: #ffffff;
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 600;
            border: none;
            transition: all 0.2s ease;
        }

        .btn-emerald:hover {
            background: #059669;
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .btn-soft {
            background: #f1f5f9;
            color: #475569;
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 600;
            border: none;
            transition: all 0.2s ease;
        }

        .btn-soft:hover {
            background: #e2e8f0;
            color: #1e293b;
        }
    </style>
</head>
<body>

<!-- Custom Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark custom-navbar py-3">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="dashboard.php">
            <i class="bi bi-shield-check text-warning fs-4"></i>
            <span>Portal Siswa</span>
        </a>
        <div class="d-flex align-items-center gap-3">
            <span class="text-light small">
                <i class="bi bi-person-circle me-1 text-emerald"></i>
                <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>
            </span>
            <a href="../auth/user.php" class="btn btn-outline-light btn-sm rounded-pill px-3">Log Keluar</a>
        </div>
    </div>
</nav>

<!-- Main Body Content -->
<div class="container py-5" style="max-width: 650px;">
    <div class="card form-card">
        <div class="form-header-custom text-center">
            <i class="bi bi-card-checklist fs-1 mb-2 d-block"></i>
            <h4 class="fw-bold mb-1">Borang Permohonan Kelab</h4>
            <p class="small opacity-75 mb-0">Sila pilih kelab yang ingin anda sertai bagi sesi ini</p>
        </div>
        
        <div class="card-body p-4 p-md-5">

            <?php if (!empty($message)): ?>
                <div class="alert alert-<?= $alertType ?> alert-dismissible fade show border-0 shadow-sm rounded-3" role="alert">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    <?= htmlspecialchars($message) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form method="POST" action="register_club.php" onsubmit="return validateForm()">
                <div class="mb-4">
                    <label for="club_id" class="form-label fw-bold text-secondary small text-uppercase">Senarai Kelab Terbuka</label>
                    <select name="club_id" id="club_id" class="form-select form-select-custom" required>
                        <option value="" selected disabled>-- Pilih Kelab Pilihan Anda --</option>
                        <?php foreach ($clubs as $club): ?>
                            <option value="<?= $club['id'] ?>">
                                📌 <?= htmlspecialchars($club['club_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="d-flex align-items-center justify-content-between gap-3 pt-3">
                    <a href="dashboard.php" class="btn btn-soft w-50 text-center">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-emerald w-50">
                        <i class="bi bi-send-fill me-1"></i> Hantar
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
function validateForm() {
    const clubSelect = document.getElementById('club_id');
    if (clubSelect.value === "" || clubSelect.value === null) {
        alert("Sila pilih satu kelab daripada senarai.");
        return false;
    }
    return true;
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
