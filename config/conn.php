<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_pendaftaran_kelab";
$conn = new mysqli("localhost", "root", "", "db_pendaftaran_kelab");

if ($conn->connect_error) {
    die("Connection failed: ". $conn->connect_error);
}
?>