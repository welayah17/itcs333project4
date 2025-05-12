<?php
session_start();
require '../db.php';
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if user is logged in
    if (!isset($_SESSION['id'])) {
        echo json_encode(["error" => "User not authenticated."]);
        exit;
    }

    $userId = $_SESSION['id'];

    // Check if user exists in the User table
    $checkUser = $db->prepare("SELECT id FROM User WHERE id = ?");
    $checkUser->execute([$userId]);
    if ($checkUser->rowCount() === 0) {
        echo json_encode(["error" => "User ID does not exist in database."]);
        exit;
    }

    // Extract and sanitize form values
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? 0;
    $phoneNumber = $_POST['phone'] ?? '';
    $status = $_POST['status'] ?? 'Available';
    $category = ($_POST['category'] === 'Other') ? ($_POST['customCategory'] ?? 'Uncategorized') : $_POST['category'];
    $publishDate = date("Y-m-d H:i:s");
    $service = "Student Marketplace";
    $imagePath = '';

    // Image upload
    if (!empty($_FILES['images']['name'][0])) {
        $uploadDir = "../uploads/";
        $filename = time() . "_" . basename($_FILES['images']['name'][0]);
        $targetPath = $uploadDir . $filename;

        if (move_uploaded_file($_FILES['images']['tmp_name'][0], $targetPath)) {
            $imagePath = $targetPath;
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

        echo json_encode(["success" => true, "message" => "Item added successfully."]);
    } catch (PDOException $e) {
        echo json_encode(["error" => "Database error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Invalid request method."]);
}
?>
