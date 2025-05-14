<?php
// update_group.php
header('Content-Type: application/json');
require 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id'], $data['subject'], $data['description'], $data['location'], $data['meeting_time'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}

$stmt = $pdo->prepare("UPDATE study_groups SET subject = ?, description = ?, location = ?, meeting_time = ? WHERE id = ?");
$stmt->execute([
    htmlspecialchars($data['subject']),
    htmlspecialchars($data['description']),
    htmlspecialchars($data['location']),
    $data['meeting_time'],
    $data['id']
]);

echo json_encode(['message' => 'Study group updated successfully']);
?>
