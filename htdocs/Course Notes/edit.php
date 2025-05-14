<?php
// edit_note.php
header("Content-Type: application/json");
include 'db.php'; // Include your database connection file

// Handle GET request to fetch the note for editing
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode(["error" => "Note ID is required."]);
        exit();
    }

    $stmt = $db->prepare("SELECT * FROM Notes WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $note = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($note) {
        echo json_encode($note);
    } else {
        http_response_code(404);
        echo json_encode(["error" => "Note not found."]);
    }
    exit();
}

// Handle POST request to update the note
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'] ?? null;
    $title = $data['title'] ?? null;
    $content = $data['content'] ?? null;
    $course_id = $data['course_id'] ?? null;

    if (!$id || !$title || !$content || !$course_id) {
        http_response_code(400);
        echo json_encode(["error" => "All fields are required."]);
        exit();
    }

    $stmt = $db->prepare("UPDATE Notes SET title = :title, content = :content, course_id = :course_id WHERE id = :id");
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(["message" => "Note updated successfully."]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Failed to update note."]);
    }
    exit();
}
?>