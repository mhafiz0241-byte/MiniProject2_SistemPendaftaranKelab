<?php
session_start();
require_once '../config/conn.php';
require_once '../Model/Club.php'; 

$clubModel = new Club($conn);
$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'add') {
        $club_name = trim($_POST['club_name']);
        $description = trim($_POST['description']);

        if (empty($club_name) || empty($description)) {
            header("Location: ../View/admin/add_club.php?error=" . urlencode("Sila isi semua ruangan yang kosong!"));
            exit();
        }

        if ($clubModel->addClub($club_name, $description)) {
            header("Location: ../View/admin/manage_clubs.php?success=" . urlencode("Kelab berjaya ditambah!"));
            exit();
        } else {
            header("Location: ../View/admin/add_club.php?error=" . urlencode("Gagal menambah kelab!"));
            exit();
        }
    }
}

if ($action === 'delete') {
    $id = $_GET['id'] ?? null;

    if ($id && $clubModel->deleteClub($id)) {
        header("Location: ../View/admin/manage_clubs.php?success=" . urlencode("Kelab berjaya dipadam!"));
        exit();
    } else {
        header("Location: ../View/admin/manage_clubs.php?error=" . urlencode("Gagal memadam kelab."));
        exit();
    }
}
?>