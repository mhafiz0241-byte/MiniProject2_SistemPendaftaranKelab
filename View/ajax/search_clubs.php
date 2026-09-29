<?php
// Sambungan ke pangkalan data menggunakan fail conn.php sedia ada
require_once '../../config/conn.php';

$search = isset($_GET['q']) ? trim($_GET['q']) : '';

if ($search !== '') {
    $stmt = $conn->prepare("SELECT * FROM clubs WHERE club_name LIKE ? OR description LIKE ? ORDER BY club_name ASC");
    $searchTerm = "%" . $search . "%";
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM clubs ORDER BY club_name ASC");
}

if ($result && $result->num_rows > 0) {
    echo '<div class="row row-cols-1 row-cols-md-2 g-3 mt-2">';
    while ($club = $result->fetch_assoc()) {
        echo '
        <div class="col">
            <div class="card h-100 border-success shadow-sm">
                <div class="card-body">
                    <h5 class="card-title fw-bold text-success">📌 ' . htmlspecialchars($club['club_name']) . '</h5>
                    <p class="card-text text-muted small">' . htmlspecialchars($club['description']) . '</p>
                </div>
                <div class="card-footer bg-transparent border-top-0 text-end">
                    <span class="badge bg-success">ID Kelab: ' . $club['id'] . '</span>
                </div>
            </div>
        </div>';
    }
    echo '</div>';
} else {
    echo '<p class="text-danger text-center mt-3">Tiada kelab yang dijumpai.</p>';
}
?>