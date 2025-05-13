<?php
header('Content-Type: application/json');
require '../db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['name'], $data['category'], $data['description'], $data['leader'])) {
    echo json_encode(['error' => 'Missing fields']);
    exit;
}

try {
    $stmt = $db->prepare("INSERT INTO clubs (name, category, description, leader) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $data['name'],
        $data['category'],
        $data['description'],
        $data['leader']
    ]);

    echo json_encode(['success' => true, 'message' => 'Club added successfully']);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
