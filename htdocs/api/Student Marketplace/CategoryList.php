<?php
require '../../db.php';
header('Content-Type: application/json');

try {
    $stmt = $db->query("SELECT DISTINCT category FROM StudentMarketplaceItem");
    $categories = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo json_encode($categories);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
