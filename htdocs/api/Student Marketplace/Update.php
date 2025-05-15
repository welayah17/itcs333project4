<?php
// CORS and content-type headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Include DB connection (returns $db as PDO)
require '../../db.php';

// --- JWT Placeholder ---
// If implementing JWT later, uncomment the following lines:
// require '../vendor/autoload.php';
// use \Firebase\JWT\JWT;

// --- Authorization Token Check ---
// $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
// $token = str_replace('Bearer ', '', $authHeader);
// $userId = verifyToken($token); // Assume verifyToken() exists
// if (!$userId) {
//     http_response_code(401);
//     echo json_encode(["error" => "Unauthorized access"]);
//     exit;
// }

// --- Get item ID from query string ---
if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(["error" => "Missing item ID"]);
    exit;
}
$itemId = $_GET['id'];

// --- Parse JSON body ---
$input = json_decode(file_get_contents("php://input"), true);

// --- Validate Required Fields ---
$required = ['title', 'description', 'price', 'phoneNumber', 'category', 'status'];
foreach ($required as $field) {
    if (empty($input[$field])) {
        http_response_code(400);
        echo json_encode(["error" => "Missing field: $field"]);
        exit;
    }
}

// --- Field-Specific Validation ---
if (!is_numeric($input['price']) || $input['price'] <= 0) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid price"]);
    exit;
}

if (!preg_match("/^\d{8}$/", $input['phoneNumber'])) {
    http_response_code(400);
    echo json_encode(["error" => "Phone number must be 8 digits"]);
    exit;
}

$validStatuses = ['Available', 'Sold', 'Pending'];
if (!in_array($input['status'], $validStatuses)) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid status"]);
    exit;
}

// --- Sanitize inputs ---
$title       = htmlspecialchars($input['title']);
$description = htmlspecialchars($input['description']);
$price       = $input['price'];
$phone       = htmlspecialchars($input['phoneNumber']);
$category    = htmlspecialchars($input['category']);
$status      = htmlspecialchars($input['status']);

try {
    // --- Update Item ---
    $stmt = $db->prepare("
        UPDATE StudentMarketplaceItem
        SET title = :title,
            description = :description,
            price = :price,
            phoneNumber = :phoneNumber,
            category = :category,
            status = :status
        WHERE id = :id
    ");

    $stmt->execute([
        ':title'       => $title,
        ':description' => $description,
        ':price'       => $price,
        ':phoneNumber' => $phone,
        ':category'    => $category,
        ':status'      => $status,
        ':id'          => $itemId
    ]);

    if ($stmt->rowCount() > 0) {
        echo json_encode([
            "status" => "success",
            "message" => "Item updated successfully"
        ]);
    } else {
        echo json_encode([
            "status" => "info",
            "message" => "No changes made or item not found"
        ]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "error" => "Database error: " . $e->getMessage()
    ]);
}
?>
