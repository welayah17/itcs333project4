<?php
// search_groups.php
header('Content-Type: application/json');
require '../db.php';

$query = isset($_GET['q']) ? '%' . $_GET['q'] . '%' : '';

$stmt = $pdo->prepare("SELECT * FROM study_groups WHERE subject LIKE :query OR description LIKE :query");
$stmt->bindValue(':query', $query, PDO::PARAM_STR);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($results);
?>
