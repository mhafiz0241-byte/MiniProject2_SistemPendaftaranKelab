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

$msg = "";
$msg_type = "";

if (isset($_POST['btn_register'])) {
    $username = trim($_POST['username']);
    $pass     = $_POST['password'];
    $role     = $_POST['role'];

    if (empty($username) || empty($pass)) {
        $msg = "Sila isi semua ruangan!";
        $msg_type = "danger";
    } elseif (strlen($pass) < 6) {
        $msg = "Kata laluan sekurang-kurangnya 6 aksara.";
        $msg_type = "danger";
    } else {
        $check_sql = "SELECT id FROM users WHERE username = ?";
        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) {
            $msg = "Nama pengguna telah wujud!";
            $msg_type = "danger";
        } else {
            $hash = password_hash($pass, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
            $insert_stmt = $conn->prepare($sql);
            $insert_stmt->bind_param("sss", $username, $hash, $role);

            if ($insert_stmt->execute()) {
                $msg = "Akaun berjaya didaftarkan! <a href='login.php'>Log masuk di sini</a>.";
                $msg_type = "success";
            } else {
                $msg = "Ralat pendaftaran ke dalam pangkalan data.";
                $msg_type = "danger";
            }
        }
    }
}

include 'header.php';
?>

<div class="container mt-5" style="max-width: 450px;">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white text-center">
            <h4>Pendaftaran Pengguna</h4>
        </div>
        <div class="card-body">
            <?php if (!empty($msg)): ?>
                <div class="alert alert-<?= $msg_type; ?>"><?= $msg; ?></div>
            <?php endif; ?>

            <form method="POST" action="register.php" onsubmit="return checkForm();">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" id="username" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required>
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

<script>
function checkForm() {
    var u = document.getElementById('username').value;
    var p = document.getElementById('password').value;

    if (u.length < 3) {
        alert("Username sekurang-kurangnya 3 aksara.");
        return false;
    }
    if (p.length < 6) {
        alert("Password sekurang-kurangnya 6 aksara.");
        return false;
    }
    return true;
}
</script>

<?php include 'footer.php'; ?>