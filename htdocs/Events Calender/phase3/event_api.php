<?php
require 'db.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        handleGet($db);
        break;
    case 'POST':
        handlePost($db);
        break;
    case 'PUT':
        handlePut($db);
        break;
    case 'DELETE':
        handleDelete($db);
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method Not Allowed']);
}

function handleGet($db) {
    $stmt = $db->query("SELECT * FROM EventCalendar ORDER BY day ASC");
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($events);
}

function handlePost($db) {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['day'], $data['description'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        return;
    }
    $stmt = $db->prepare("INSERT INTO EventCalendar (day, description) VALUES (?, ?)");
    $stmt->execute([$data['day'], $data['description']]);
    echo json_encode(['status' => 'success']);
}

function handlePut($db) {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['id'], $data['description'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        return;
    }
    $stmt = $db->prepare("UPDATE EventCalendar SET description = ? WHERE id = ?");
    $stmt->execute([$data['description'], $data['id']]);
    echo json_encode(['status' => 'updated']);
}

function handleDelete($db) {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing ID']);
        return;
    }
    $stmt = $db->prepare("DELETE FROM EventCalendar WHERE id = ?");
    $stmt->execute([$data['id']]);
    echo json_encode(['status' => 'deleted']);
}
?>
