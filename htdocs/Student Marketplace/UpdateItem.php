<?php
require '../db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$id = $_GET['id'] ?? null;

if (!$id) {
    echo json_encode(["error" => "Missing item ID"]);
    exit;
}

try {
    $stmt = $db->prepare("UPDATE StudentMarketplaceItem SET 
        title = :title, 
        description = :description,
        price = :price,
        category = :category,
        status = :status,
        phoneNumber = :phoneNumber,
        publishDate = :publishDate,
        service = :service,
        image = :image,
        popularity = :popularity,
        userId = :userId
        WHERE id = :id");

    $stmt->execute([
        ':title' => $data['title'],
        ':description' => $data['description'],
        ':price' => $data['price'],
        ':category' => $data['category'],
        ':status' => $data['status'],
        ':phoneNumber' => $data['phoneNumber'],
        ':publishDate' => $data['publishDate'],
        ':service' => $data['service'],
        ':image' => $data['image'],
        ':popularity' => $data['popularity'],
        ':userId' => $data['userId'],
        ':id' => $id
    ]);

    echo json_encode(["message" => "Item updated successfully"]);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
