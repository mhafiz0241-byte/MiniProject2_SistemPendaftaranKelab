<?php
session_start();
include 'conn.php';

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: dashboard.php");
    } else {
        header("Location: dashboard_student.php");
    }
    exit();
}

$error = "";

if (isset($_POST['btn_login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Sila masukkan username dan password.";
    } else {
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();

            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id']  = $row['id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['role']     = $row['role'];

                if ($row['role'] == 'admin') {
                    header("Location: dashboard.php");
                } else {
                    header("Location: dashboard_student.php");
                }
                exit();
            } else {
                $error = "Kata laluan salah!";
            }
        } else {
            $error = "Pengguna tidak wujud!";
        }
    }
}

include 'header.php';
?>

<div class="container mt-5" style="max-width: 450px;">
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white text-center">
            <h4>Log Masuk Sistem</h4>
        </div>
        <div class="card-body">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="login.php">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <button type="submit" name="btn_login" class="btn btn-success w-100">Log Masuk</button>
            </form>
            <p class="mt-3 text-center">Belum ada akaun? <a href="register.php">Daftar di sini</a></p>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>