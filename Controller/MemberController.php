<?php
session_start();
require_once '../config/conn.php';
require_once '../Model/Club.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../View/auth/login.php?error=" . urlencode("Sila log masuk sebagai pelajar terlebih dahulu."));
    exit();
}

$clubModel = new Club($conn);
$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ditukar daripada 'register_club' kepada 'register' mengikut borang HTML
    if ($action === 'register') {
        $user_id = $_SESSION['user_id']; 
        $club_id = trim($_POST['club_id']); 

        if (empty($club_id)) {
            header("Location: ../View/student/register_club.php?error=" . urlencode("Sila pilih satu kelab!"));
            exit();
        }

        if ($clubModel->registerStudentToClub($user_id, $club_id)) {
            header("Location: ../View/student/dashboard_student.php?success=" . urlencode("Kelab berjaya didaftarkan!"));
            exit();
        } else {
            header("Location: ../View/student/register_club.php?error=" . urlencode("Gagal mendaftar kelab atau anda sudah menyertai kelab ini."));
            exit();
        }
    }
}

if ($action === 'cancel') {
    $registration_id = $_GET['id'] ?? null;

    if ($registration_id && $clubModel->cancelRegistration($registration_id)) {
        header("Location: ../View/student/dashboard_student.php?success=" . urlencode("Pendaftaran kelab anda telah dibatalkan."));
        exit();
    } else {
        header("Location: ../View/student/dashboard_student.php?error=" . urlencode("Gagal membatalkan pendaftaran."));
        exit();
    }
}
?>