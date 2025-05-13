<?php
session_start();
require '../db.php';
header('Content-Type: application/json');

$raw = file_get_contents("php://input");

if (!$raw) {
    echo json_encode(["error" => "No data received", "raw" => $raw]);
    exit;
}


$data = json_decode($raw, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(["error" => "Invalid JSON", "raw" => $raw]);
    exit;
}


if (!isset($data['id'])) {
    echo json_encode(["error" => "Missing ID", "received" => $data]);
    exit;
}

// Proceed to delete
try {
    $stmt = $db->prepare("DELETE FROM clubs WHERE id = ?");
    $stmt->execute([$data['id']]);
    echo json_encode(["success" => true, "message" => "Club deleted successfully"]);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>