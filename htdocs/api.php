<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$host = "127.0.0.1";
$user = getenv("db_user");
$pass = getenv("db_pass");
$db   = getenv("db_name");

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$path = $_GET['path'] ?? '';

if ($method == 'GET' && $path == 'clubs') {
    // List clubs
    $stmt = $pdo->query("SELECT * FROM clubs ORDER BY id DESC");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}
elseif ($method == 'POST' && $path == 'clubs') {
    // Add a new club
    $data = json_decode(file_get_contents("php://input"), true);
    if (!isset($data['name'])) {
        http_response_code(400);
        echo json_encode(["error" => "Name is required"]);
        exit;
    }
    $stmt = $pdo->prepare("INSERT INTO clubs (name, description, category, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$data['name'], $data['description'], $data['category']]);
    echo json_encode(["message" => "Club added"]);
}
elseif ($method == 'PUT' && $path == 'clubs') {
    // Update club
    $data = json_decode(file_get_contents("php://input"), true);
    if (!isset($data['id'])) {
        http_response_code(400);
        echo json_encode(["error" => "ID is required"]);
        exit;
    }
    $stmt = $pdo->prepare("UPDATE clubs SET name = ?, description = ?, category = ? WHERE id = ?");
    $stmt->execute([$data['name'], $data['description'], $data['category'], $data['id']]);
    echo json_encode(["message" => "Club updated"]);
}
elseif ($method == 'DELETE' && $path == 'clubs') {
    // Delete club
    $data = json_decode(file_get_contents("php://input"), true);
    if (!isset($data['id'])) {
        http_response_code(400);
        echo json_encode(["error" => "ID is required"]);
        exit;
    }
    $stmt = $pdo->prepare("DELETE FROM clubs WHERE id = ?");
    $stmt->execute([$data['id']]);
    echo json_encode(["message" => "Club deleted"]);
}
else {
    http_response_code(404);
    echo json_encode(["error" => "Invalid request"]);
}
