<?php
header("Content-Type: application/json");
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    $title = trim($data['title'] ?? '');
    $content = trim($data['content'] ?? '');
    $course_id = $data['course_id'] ?? null;

    if (!$title || !$content || !$course_id) {
        http_response_code(400);
        echo json_encode(["error" => "All fields are required."]);
        exit;
    }

    try {
        $stmt = $db->prepare("INSERT INTO Notes (title, content, course_id) VALUES (:title, :content, :course_id)");
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
        $stmt->execute();

        http_response_code(201);
        echo json_encode(["message" => "Note created successfully."]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["error" => "Database error: " . $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Only POST method is allowed."]);
}
?>
