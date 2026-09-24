<?php
session_start();
require_once '../config/conn.php';
require_once '../models/Club.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../views/auth/login.php?error=" . urlencode("Please log in as a student first."));
    exit();
}

$clubModel = new Club($conn);
$action = isset($_GET['action']) ? $_GET['action'] : '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'register_club') {
        $user_id = $_SESSION['user_id']; 
        $club_id = trim($_POST['club_id']); 

        if (empty($club_id)) {
            header("Location: ../views/student/register_club.php?error=" . urlencode("Please choose one club only!"));
            exit();
        }

        if ($clubModel->registerStudentToClub($user_id, $club_id)) {
            header("Location: ../views/student/my_registrations.php?success=" . urlencode("Club registered succesfully!"));
            exit();
        } else {
            header("Location: ../views/student/register_club.php?error=" . urlencode("Failed to register the club or you haved joined this club."));
            exit();
        }
    }
}

if ($action === 'cancel') {
    $registration_id = $_GET['id'] ?? null;

    if ($registration_id && $clubModel->cancelRegistration($registration_id)) {
        header("Location: ../views/student/my_registrations.php?success=" . urlencode("Your club registered has been canceled"));
        exit();
    } else {
        header("Location: ../views/student/my_registrations.php?error=" . urlencode("Failed to cancel the register."));
        exit();
    }
}
?>