<?php
session_start();
require_once '../config/conn.php';
require_once '../models/Club.php';

$clubModel = new Club($conn);
$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'add') {
        $club_name = trim($_POST['club_name']);
        $description = trim($_POST['description']);

        if (empty($club_name) || empty($description)) {
            header("Location: ../views/admin/add_club.php?error=" . urlencode("Please fill the blanks!"));
            exit();
        }

        if ($clubModel->addClub($club_name, $description)) {
            header("Location: ../views/admin/dashboard.php?success=" . urlencode("The club has succesfully been added!"));
            exit();
        } else {
            header("Location:../views/admin/add_club.php?error=" . urlencode(Failed to add!));
            exit();
        }
    }
}

if ($action === 'delete') {
    $id = $_GET['id'] ?? null;

    if ($id && $clubModel->deleteClub($id)) {
        header("Location:../views/admin/dashboard.php?success=" . urlencode("The club has been succesfully deleted!"));
        exit();
    } else {
        header("Location:../views/admin/dashboard.php?error=" . urlencode("Failed to delete the club."));
        exit();
    }
}
?>