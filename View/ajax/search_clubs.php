<?php
require_once '../../config/conn.php';

$query = isset($_GET['q']) ? trim($_GET['q']) : '';

if ($query !== '') {
    $stmt = $pdo->prepare("SELECT * FROM clubs WHERE club_name LIKE ? OR description LIKE ? ORDER BY id DESC");
    $searchTerm = "%" . $query . "%";
    $stmt->execute([$searchTerm, $searchTerm]);
} else {
    $stmt = $pdo->query("SELECT * FROM clubs ORDER BY id DESC");
}

$clubs = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($clubs) > 0) {
    echo '<div class="row g-3 mt-2">';
    foreach ($clubs as $club) {
        echo '
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 p-3 style="background: #ffffff;">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge bg-emerald-subtle text-success px-3 py-2 rounded-pill fw-bold">ID #' . htmlspecialchars($club['id']) . '</span>
                            <small class="text-muted" style="font-size: 0.75rem;"><i class="bi bi-clock me-1"></i>' . date('d M Y', strtotime($club['created_at'])) . '</small>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">' . htmlspecialchars($club['club_name']) . '</h5>
                        <p class="text-secondary small mb-3">' . htmlspecialchars($club['description']) . '</p>
                    </div>
                </div>
            </div>
        </div>';
    }
    echo '</div>';
} else {
    echo '
    <div class="text-center py-5 bg-white rounded-4 shadow-sm mt-3">
        <i class="bi bi-search text-muted fs-1 d-block mb-2"></i>
        <h6 class="fw-bold text-secondary">Tiada Kelab Ditemui</h6>
        <p class="text-muted small mb-0">Cuba cari dengan kata kunci yang lain.</p>
    </div>';
}
?>
