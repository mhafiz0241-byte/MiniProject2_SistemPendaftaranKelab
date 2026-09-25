<?php
session_start();

// Naik dua tingkat dari View/auth/ untuk sampai ke root, kemudian masuk ke config/conn.php
require_once '../../config/conn.php';

$error = "";
$success = "";

if (isset($_POST['btn_register'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role = $_POST['role'] ?? 'student'; // Ambil role atau default ke student

    if (empty($username) || empty($password)) {
        $error = "Sila lengkapkan semua maklumat.";
    } else {
        // Semak sama ada username sudah wujud
        $stmt_check = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt_check->bind_param("s", $username);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows >  0) {
            $error = "Username telah digunakan. Sila pilih username lain.";
        } else {
            $stmt_check->close();

            // Masukkan pengguna baru (password di-hash menggunakan password_hash)
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt_insert = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
            $stmt_insert->bind_param("sss", $username, $hashed_password, $role);

            if ($stmt_insert->execute()) {
                $success = "Pendaftaran berjaya! Sila <a href='login.php'>Log Masuk</a>.";
            } else {
                $error = "Ralat berlaku semasa pendaftaran.";
            }
            $stmt_insert->close();
        }
    }
}

// Panggil header dari View/layout/header.php
include '../layout/header.php';
?>

<div class="container mt-5" style="max-width: 450px;">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white text-center">
            <h4>Pendaftaran Pengguna</h4>
        </div>
        <div class="card-body">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= $error; ?></div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?= $success; ?></div>
            <?php endif; ?>

            <form method="POST" action="register.php">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Peranan (Role)</label>
                    <select name="role" class="form-select">
                        <option value="student">Student</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <button type="submit" name="btn_register" class="btn btn-primary w-100">Daftar</button>
            </form>
            <p class="mt-3 text-center">Sudah ada akaun? <a href="login.php">Log masuk</a></p>
        </div>
    </div>
</div>

<?php 
// Panggil footer dari View/layout/footer.php
include '../layout/footer.php'; 
?>