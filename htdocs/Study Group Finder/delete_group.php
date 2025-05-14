<?php
// delete_group.php
header('Content-Type: application/json');
require 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing group ID']);
    exit;
}

$stmt = $pdo->prepare("DELETE FROM study_groups WHERE id = ?");
$stmt->execute([$data['id']]);

echo json_encode(['message' => 'Study group deleted successfully']);
?>
