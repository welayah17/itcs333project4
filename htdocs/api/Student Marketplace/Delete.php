<?php
header('Content-Type: application/json');
require '../db.php';

// Allow only DELETE method
if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Parse DELETE body (x-www-form-urlencoded)
parse_str(file_get_contents("php://input"), $body);
$id = $body['id'] ?? null;

if (!$id) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing item ID']);
    exit;
}

try {
    $stmt = $db->prepare("DELETE FROM StudentMarketplaceItem WHERE id = ?");
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Item not found']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
