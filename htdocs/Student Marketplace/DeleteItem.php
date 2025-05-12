<?php
require '../db.php';
header('Content-Type: application/json');

$id = $_GET['id'] ?? null;

if (!$id) {
    echo json_encode(["error" => "Missing item ID"]);
    exit;
}

try {
    $stmt = $db->prepare("DELETE FROM StudentMarketplaceItem WHERE id = :id");
    $stmt->execute([':id' => $id]);

    echo json_encode(["message" => "Item deleted successfully"]);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
