<?php
require_once 'conn.php';
$query = isset($_GET['q']) ? trim($_GET['q']) : '';

if ($query !== ''){
  $stmt = $pdo->prepare("SELECT * FROM clubs WHERE club_name LIKE ? OR category LIKE ? ORDER BY id DESC");
  $searchTerm = "%" . $query . "%";
  $stmt->execute([$searchTerm, $searchTerm]);
} else {
  $stmt = $pdo->query("SELECT * FROM clubs ORDER BY id DESC");
}
$clubs = $stmt->fetchAll();

if (count($clubs) > 0){
  echo '<table class="table table-bordered table-hover mt-3">
        <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Club Name</th>
            <th>Category</th>
            <th>Description</th>
    </tr>
    </thead>
    <tbody>';
  foreach ($clubs as $club) {
    echo '<tr>
            <td>' . htmlspecialchars($club['id']) . '</td>
            <td>' . htmlspecialchars($club['club_name']) . '</td>
            <td><span class="badge bg-info text-dark">' . htmlspecialchars($club['category']) . '</span></td>
            <td>' . htmlspecialchars($club['description']) . '</td>
            </tr>';
  }
  echo '</tbody></table>';
}else{
  echo'<div class="alert alert-warning mt-3">No clubs found matching your query.</div>';
  ?>
