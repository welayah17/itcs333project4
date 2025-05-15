<?php
require 'db.php';

header('Content-Type: application/json');

try {
    $db->exec("SET FOREIGN_KEY_CHECKS=1");

    $db->exec("
        CREATE TABLE IF NOT EXISTS EventCalendar (
            id INT AUTO_INCREMENT PRIMARY KEY,
            day INT NOT NULL,
            description VARCHAR(255) NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );
    ");

    echo json_encode(['status' => 'success', 'message' => 'EventCalendar table created successfully.']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    exit;
}
?>
