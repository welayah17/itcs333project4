<?php
// Enable CORS and JSON output
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include DB connection
require '../../db.php';

// Get the item ID from query string
if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(["error" => "Missing item ID in the URL"]);
    exit;
}

$itemId = intval($_GET['id']); // Sanitize input

try {
    $stmt = $db->prepare("SELECT * FROM StudentMarketplaceItem WHERE id = :id");
    $stmt->bindParam(':id', $itemId, PDO::PARAM_INT);
    $stmt->execute();
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($item) {
        echo json_encode($item);
    } else {
        http_response_code(404);
        echo json_encode(["error" => "Item not found"]);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database error", "message" => $e->getMessage()]);
}
?>
