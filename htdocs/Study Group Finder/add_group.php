// add_group.php
header('Content-Type: application/json');
require 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['subject'], $data['description'], $data['location'], $data['meeting_time'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}

$stmt = $pdo->prepare("INSERT INTO study_groups (subject, description, location, meeting_time, created_at) VALUES (?, ?, ?, ?, NOW())");
$stmt->execute([
    htmlspecialchars($data['subject']),
    htmlspecialchars($data['description']),
    htmlspecialchars($data['location']),
    $data['meeting_time']
]);

echo json_encode(['message' => 'Study group added successfully']);
