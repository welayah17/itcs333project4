<?php
header('Content-Type: application/json');

// Database connection using secrets
$host = '127.0.0.1';
$dbname = getenv("db_name");
$user = getenv("db_user");
$pass = getenv("db_pass");

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Handle the endpoint path (e.g. ?path=clubs)
    $path = $_GET['path'] ?? '';

    if ($path === 'clubs') {
        $stmt = $pdo->query("SELECT * FROM clubs");
        $clubs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($clubs);
    } else {
        echo json_encode(["error" => "Invalid path or no data found"]);
    }

} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
