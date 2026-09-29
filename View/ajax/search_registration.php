<?php
require_once __DIR__ . '/../../config/conn.php';

$search = isset($_GET['q']) ? trim($_GET['q']) : '';

$sql = "SELECT r.*, u.username AS student_name, c.club_name 
        FROM registrations r 
        JOIN users u ON r.user_id = u.id 
        JOIN clubs c ON r.club_id = c.id";

if ($search !== '') {
    $sql .= " WHERE u.username LIKE ? OR c.club_name LIKE ? ORDER BY r.registration_date DESC";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%" . $search . "%";
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql .= " ORDER BY r.registration_date DESC";
    $result = $conn->query($sql);
}

if ($result && $result->num_rows > 0) {
    $no = 1;
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $no++ . '</td>';
        echo '<td class="fw-bold">' . htmlspecialchars($row['student_name']) . '</td>';
        echo '<td><span class="badge bg-primary">' . htmlspecialchars($row['club_name']) . '</span></td>';
        echo '<td>' . $row['registration_date'] . '</td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="4" class="text-center text-danger py-3">Tiada rekod pendaftaran dijumpai.</td></tr>';
}
?>