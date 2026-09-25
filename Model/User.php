<?php
// Start session management
session_start();

// Include database connection (adjust path based on your folder structure)
require_once __DIR__ . '/config/conn.php';

// Redirect if user is already logged in
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: view/admin/dashboard.php");
    } else {
        header("Location: view/student/dashboard.php");
    }
    exit();
}

$message = '';
$alertType = 'danger';

// Server-Side Form Processing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? trim($_POST['action']) : '';
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    // Server-side Validation
    if (empty($username) || empty($password)) {
        $message = "All fields are required.";
    } elseif (strlen($username) < 3) {
        $message = "Username must be at least 3 characters long.";
    } elseif (strlen($password) < 6) {
        $message = "Password must be at least 6 characters long.";
    } else {
        if ($action === 'register') {
            // Check if username already exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$username]);

            if ($stmt->rowCount() > 0) {
                $message = "Username is already taken. Please choose another.";
            } else {
                // Secure Password Hashing
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $defaultRole = 'student'; // Default registration role

                // Insert into database using Prepared Statements
                $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
                if ($stmt->execute([$username, $hashedPassword, $defaultRole])) {
                    $message = "Registration successful! You can now log in.";
                    $alertType = 'success';
                } else {
                    $message = "Failed to register. Please try again.";
                }
            }
        } elseif ($action === 'login') {
            // Retrieve user record
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verify Password Hash
            if ($user && password_verify($password, $user['password'])) {
                // Secure Session Initialization
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Role-Based Authorization Redirect
                if ($user['role'] === 'admin') {
                    header("Location: view/admin/dashboard.php");
                } else {
                    header("Location: view/student/dashboard.php");
                }
                exit();
            } else {
                $message = "Invalid username or password.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Portal - Sistem Pendaftaran Kelab</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .auth-card {
            max-width: 420px;
            margin: 80px auto;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card auth-card">
        <div class="card-header bg-primary text-white text-center py-3">
            <h4 class="mb-0">Sistem Pendaftaran Kelab</h4>
            <small>User Authentication</small>
        </div>
        <div class="card-body p-4">

            <!-- Feedback Alert Display -->
            <?php if (!empty($message)): ?>
                <div class="alert alert-<?= $alertType ?> alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($message) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- Client-side Validated Form -->
            <form method="POST" action="user.php" onsubmit="return validateForm()">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                    <div class="invalid-feedback" id="userError"></div>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                    <div class="invalid-feedback" id="passError"></div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" name="action" value="login" class="btn btn-primary btn-lg fs-6">Login</button>
                    <button type="submit" name="action" value="register" class="btn btn-outline-secondary btn-lg fs-6">Register</button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- Client-side JavaScript Validation -->
<script>
function validateForm() {
    const usernameInput = document.getElementById('username');
    const passwordInput = document.getElementById('password');
    let isValid = true;

    // Reset styles
    usernameInput.classList.remove('is-invalid');
    passwordInput.classList.remove('is-invalid');

    if (usernameInput.value.trim().length < 3) {
        usernameInput.classList.add('is-invalid');
        document.getElementById('userError').innerText = "Username must be at least 3 characters long.";
        isValid = false;
    }

    if (passwordInput.value.trim().length < 6) {
        passwordInput.classList.add('is-invalid');
        document.getElementById('passError').innerText = "Password must be at least 6 characters long.";
        isValid = false;
    }

    return isValid;
}
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
