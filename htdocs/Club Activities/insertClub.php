<?php
session_start();
require '../db.php';
header('Content-Type: application/json');


$rawData = file_get_contents("php://input");

// Check if data is empty
if (!$rawData) {
    echo json_encode([
        'error' => 'No data received',
        'raw' => $rawData
    ]);
    exit;
}

// Decode JSON
$data = json_decode($rawData, true);

// Check if JSON decoding was successful
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode([
        'error' => 'Invalid JSON',
        'raw' => $rawData
    ]);
    exit;
}

// Validate fields
if (!isset($data['name'], $data['category'], $data['description'], $data['leader'])) {
    echo json_encode([
        'error' => 'Missing fields',
        'received' => $data
    ]);
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
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
