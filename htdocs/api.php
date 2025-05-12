<?php
header('Content-Type: application/json');

try {
      session_start();
    require 'db.php';
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
