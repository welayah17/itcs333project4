<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Get path from query parameter
$path = $_GET['path'] ?? '';


$host = getenv("db_host");
$db   = getenv("db_name");
$user = getenv("db_user");
$pass = getenv("db_pass");

$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    if ($path === 'clubs') {
        $stmt = $pdo->query("SELECT name, category, description, leader FROM clubs");
        $data = $stmt->fetchAll();
        echo json_encode($data);
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Invalid path"]);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
