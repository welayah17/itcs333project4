<?php
session_start();
require '../db.php';
header('Content-Type: application/json');
$data = json_decode(file_get_contents("php://input"), true);
try {
    $stmt = $db->query("SELECT * FROM clubs ORDER BY id DESC");
    $clubs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($clubs);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
