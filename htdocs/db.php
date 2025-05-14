<?php
$host = "127.0.0.1";
$dbname = getenv("db_name");
$user = getenv("db_user");
$pass = getenv("db_pass");

// Optional: Check if env vars are set
if (!$dbname || !$user || !$pass) {
    echo json_encode(["error" => "Missing database environment variables."]);
    exit;
}

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Optional: return a success message (useful for testing)
    // echo json_encode(["success" => "Connected to database!"]);
} catch (PDOException $e) {
    echo json_encode(["error" => "Database connection error."]);
    // Optional: log the error to a file instead
    // error_log($e->getMessage());
    exit;
}
?>
