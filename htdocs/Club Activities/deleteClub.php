<?php
header('Content-Type: application/json');
require '../db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['id'])) {
    echo json_encode(['error' => 'Missing ID']);
    exit;
}

try {
    $stmt = $db->prepare("DELETE FROM clubs WHERE id = ?");
    $stmt->execute([$data['id']]);
    echo json_encode(['success' => true, 'message' => 'Club deleted successfully']);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
