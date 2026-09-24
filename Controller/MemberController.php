<?php
session_start();
require_once '../config/conn.php';
require_once '../models/Club.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../views/auth/login.php?error=" . urlencode("Sila log masuk sebagai pelajar terlebih dahulu."));
    exit();
}

$clubModel = new Club($conn);
$action = isset($_GET['action']) ? $_GET['action'] : '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'register_club') {
        $user_id = $_SESSION['user_id']; 
        $club_id = trim($_POST['club_id']); 

        if (empty($club_id)) {
            header("Location: ../views/student/register_club.php?error=" . urlencode("Sila pilih salah satu kelab!"));
            exit();
        }
        
        if ($clubModel->registerStudentToClub($user_id, $club_id)) {
            header("Location: ../views/student/my_registrations.php?success=" . urlencode("Tahniah! Pendaftaran kelab berjaya."));
            exit();
        } else {
            header("Location: ../views/student/register_club.php?error=" . urlencode("Gagal mendaftar kelab atau anda sudah mendaftar kelab ini."));
            exit();
        }
    }
}

if ($action === 'cancel') {
    $registration_id = $_GET['id'] ?? null;

    if ($registration_id && $clubModel->cancelRegistration($registration_id)) {
        header("Location: ../views/student/my_registrations.php?success=" . urlencode("Pendaftaran kelab berjaya dibatalkan."));
        exit();
    } else {
        header("Location: ../views/student/my_registrations.php?error=" . urlencode("Gagal membatalkan pendaftaran."));
        exit();
    }
}
?>