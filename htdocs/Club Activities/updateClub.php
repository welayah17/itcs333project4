<?php
session_start();
header('Content-Type: application/json');
require '../db.php';


$raw = file_get_contents("php://input");
$data = json_decode($raw, true);

// Validate data
if (
    !$data ||
    !isset($data['id'], $data['name'], $data['category'], $data['description'], $data['leader'])
) {
    echo json_encode([
        'error' => 'Missing ID or fields',
        'raw' => $raw
    ]);
    exit;
}

try {
    $stmt = $db->prepare("UPDATE clubs SET name = ?, category = ?, description = ?, leader = ? WHERE id = ?");
    $stmt->execute([
        $data['name'],
        $data['category'],
        $data['description'],
        $data['leader'],
        $data['id']
    ]);

    echo json_encode(['success' => true, 'message' => 'Club updated successfully']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
