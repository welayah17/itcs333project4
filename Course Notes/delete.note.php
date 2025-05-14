<?php
// delete_note.php
header("Content-Type: application/json");
include 'db.php'; // Include your database connection file

// Handle POST request to delete the note
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode(["error" => "Note ID is required."]);
        exit();
    }

    $stmt = $db->prepare("DELETE FROM Notes WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(["message" => "Note deleted successfully."]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Failed to delete note."]);
    }
    exit();
}
?>