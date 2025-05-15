<?php
session_start();
require '../../db.php';

// Set JSON content type for all responses
header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(["error" => "Invalid request method. Only POST allowed."]);
    exit;
}

// Check user authentication
if (!isset($_SESSION['id'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(["error" => "User not authenticated."]);
    exit;
}

$userId = $_SESSION['id'];

// Verify user exists
$stmtUser = $db->prepare("SELECT id FROM User WHERE id = ?");
$stmtUser->execute([$userId]);
if ($stmtUser->rowCount() === 0) {
    http_response_code(401);
    echo json_encode(["error" => "User does not exist."]);
    exit;
}

// Required fields validation helper
function validate_field($fieldName) {
    if (empty($_POST[$fieldName])) {
        http_response_code(400);
        echo json_encode(["error" => "Field '$fieldName' is required."]);
        exit;
    }
}

// Validate required fields
validate_field('title');
validate_field('description');
validate_field('price');
validate_field('phone');
validate_field('status');
validate_field('category');

$title = htmlspecialchars(trim($_POST['title']));
$description = htmlspecialchars(trim($_POST['description']));
$price = floatval($_POST['price']);
$phoneNumber = htmlspecialchars(trim($_POST['phone']));
$status = htmlspecialchars(trim($_POST['status']));
$category = $_POST['category'] === 'Other' ? (htmlspecialchars(trim($_POST['customCategory'] ?? 'Uncategorized'))) : htmlspecialchars(trim($_POST['category']));
$publishDate = date("Y-m-d H:i:s");
$service = "Student Marketplace";
$imagePath = '';

// Handle image upload (optional)
if (!empty($_FILES['images']['name'][0])) {
    $uploadDir = "../Images/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $filename = time() . "_" . basename($_FILES['images']['name'][0]);
    $targetPath = $uploadDir . $filename;

    if (move_uploaded_file($_FILES['images']['tmp_name'][0], $targetPath)) {
        // Store relative path to image in DB
        $imagePath = "Images/" . $filename;
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Failed to upload image."]);
        exit;
    }
}

try {
    $stmt = $db->prepare("
        INSERT INTO StudentMarketplaceItem
            (title, description, price, category, status, phoneNumber, publishDate, service, image, userId)
        VALUES
            (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $title,
        $description,
        $price,
        $category,
        $status,
        $phoneNumber,
        $publishDate,
        $service,
        $imagePath,
        $userId
    ]);

    http_response_code(201); // Created
    echo json_encode(["success" => true, "message" => "Item added successfully."]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
?>
