<?php
session_start();
require '../db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $phoneNumber = $_POST['phone'];
    $status = $_POST['status'];
    $category = ($_POST['category'] === 'Other') ? $_POST['customCategory'] : $_POST['category'];
    $publishDate = date("Y-m-d H:i:s");
    $service = "Student Marketplace"; // Optional or from another field
    $imagePath = ''; // Handle file upload below
    $userId = $_SESSION['user_id'] ?? 1; // Replace with real session ID

    // Handle image upload
    if (!empty($_FILES['images']['name'][0])) {
        $targetDir = "../uploads/";
        $filename = basename($_FILES["images"]["name"][0]);
        $targetFile = $targetDir . time() . "_" . $filename;

        if (move_uploaded_file($_FILES["images"]["tmp_name"][0], $targetFile)) {
            $imagePath = $targetFile;
        }
    }

    try {
        $stmt = $db->prepare("INSERT INTO StudentMarketplaceItem 
        (title, description, price, category, status, phoneNumber, publishDate, service, image, userId)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            $title, $description, $price, $category,
            ($status === "Available" ? 1 : 0), $phoneNumber,
            $publishDate, $service, $imagePath, $userId
        ]);

        echo "success";
    } catch (PDOException $e) {
        echo "error: " . $e->getMessage();

    }
}
?>