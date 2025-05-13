<?php
header('Content-Type: application/json');
require '../db.php';

try {
    $stmt = $db->query("SELECT * FROM clubs ORDER BY id DESC");
    $clubs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($clubs);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
