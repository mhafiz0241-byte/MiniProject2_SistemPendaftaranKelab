<?php
session_start();
require_once '../config/conn.php';
require_once '../models/User.php';

$userModel = new User($conn);
$action = isset($_GET['action']) ? $_GET['action']; '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'login') {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        if (empty($username) || empty($password)) {
            header("Location: ../views/auth/login.php?error=" .urlencode("Sila isi semua maklumat!"));
            exit();
        }

        $user = $userModel->login($username, $password);

        if ($user) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {
                header("Location: ../views/admin/dashboard.php");
                exit();
            } else {
                header("Location: ../views/student/dashboard.php");
                exit();
            } else {
                header("Location: ../views/auth/login.php?error=" . urlencode("incorrect username or password!"));
                exit();
            }
        }
    }

    elseif ($action === 'register') {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $confirm_password = trim($_POST['confirm_password']);

        if (empty($username) || empty($password) || empty($confirm_password)) {
            header("Location: ../views/auth/register.php?error=" .urlencode("please fill the blank!"));
            exit();
        }

        if ($password !== $confirm_password) {
            header("Location: ../views/auth/register.php?error=" .urlencode("Password is not matching!"));
            exit();
        }

        if ($userModel->isUsernameTaken($username)) {
            header("Location: ../views/auth/register.php?error=" .urlencode("Username is exist!"));
            exit();
        }

        if ($userModel->register($username, $password)) {
            header("Location: ../views/auth/login.php?success=" .urlencode("Register has been succesfully!Please log in."));
            exit();
        } else {
            header("Location: ../views/auth/register.php?error=" .urlencode("Failed to register account."));
            exit();
        }
    }
}
?>