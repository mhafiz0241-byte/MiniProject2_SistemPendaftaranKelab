<?php
session_start();

// Sambungan database (naik dua tingkat dari View/student/ ke root, masuk config/)
require_once '../../config/conn.php';

// Semak keselamatan sesi pengguna
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: ../auth/login.php");
    exit();
}

$error = "";
$success = "";

// Logik untuk proses daftar kelab (jika borang dihantar)
if (isset($_POST['btn_register_club'])) {
    $club_id = $_POST['club_id'] ?? '';
    $user_id = $_SESSION['user_id'];

    if (empty($club_id)) {
        $error = "Sila pilih kelab terlebih dahulu.";
    } else {
        // Semak jika pelajar sudah berdaftar dengan kelab ini
        $stmt_check = $conn->prepare("SELECT id FROM registrations WHERE user_id = ? AND club_id = ?");
        $stmt_check->bind_param("ii", $user_id, $club_id);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $error = "Anda sudah berdaftar dengan kelab ini!";
        } else {
            $stmt_check->close();

            // Masukkan data pendaftaran kelab
            $stmt_insert = $conn->prepare("INSERT INTO registrations (user_id, club_id) VALUES (?, ?)");
            $stmt_insert->bind_param("ii", $user_id, $club_id);

            if ($stmt_insert->execute()) {
                $success = "Tahniah! Anda berjaya mendaftar kelab ini.";
            } else {
                $error = "Ralat berlaku semasa pendaftaran kelab.";
            }
            $stmt_insert->close();
        }
    }
}

// Panggil senarai kelab dari database untuk dropdown
$clubs_result = $conn->query("SELECT * FROM clubs");

// Panggil header layout (naik satu tingkat ke View/, masuk layout/)
include '../layout/header.php';
?>

<div class="container mt-5" style="max-width: 600px;">
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white text-center">
            <h4>Daftar Kelab Baru</h4>
        </div>
        <div class="card-body">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= $error; ?></div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?= $success; ?></div>
            <?php endif; ?>

            <form method="POST" action="register_club.php">
                <div class="mb-3">
                    <label class="form-label">Pilih Kelab:</label>
                    <select name="club_id" class="form-select" required>
                        <option value="">-- Sila Pilih Kelab --</option>
                        <?php while ($club = $clubs_result->fetch_assoc()): ?>
                            <option value="<?= $club['id']; ?>"><?= htmlspecialchars($club['club_name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <button type="submit" name="btn_register_club" class="btn btn-primary w-100 mb-2">Daftar Sekarang</button>
                
                <!-- Pautan Kembali yang telah dibetulkan kepada dashboard_student.php -->
                <a href="dashboard_student.php" class="btn btn-secondary w-100">Kembali</a>
            </form>
        </div>
    </div>
</div>

<?php 
// Panggil footer layout
include '../layout/footer.php'; 
?>