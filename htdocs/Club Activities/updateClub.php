<?php
session_start();
require '../db.php';

// Ensure the request is POST
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

// Read and decode JSON input
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!is_array($data)) {
    echo json_encode(['success' => false, 'error' => 'Invalid JSON']);
    exit;
}

// Validate required fields
if (
    empty($data['id']) ||
    empty($data['name']) ||
    empty($data['category']) ||
    empty($data['description']) ||
    empty($data['leader'])
) {
    echo json_encode(['success' => false, 'error' => 'All fields are required']);
    exit;
}

try {
    $stmt = $db->prepare("UPDATE clubs SET name = :name, category = :category, description = :description, leader = :leader WHERE id = :id");
    $stmt->execute([
        ':name' => $data['name'],
        ':category' => $data['category'],
        ':description' => $data['description'],
        ':leader' => $data['leader'],
        ':id' => $data['id']
    ]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Update failed: ' . $e->getMessage()]);
}
?>