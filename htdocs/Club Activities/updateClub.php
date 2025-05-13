<?php
header('Content-Type: application/json');
require 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['id'], $data['name'], $data['category'], $data['description'], $data['leader'])) {
    echo json_encode(['error' => 'Missing fields']);
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
    echo json_encode(['error' => $e->getMessage()]);
}
?>
