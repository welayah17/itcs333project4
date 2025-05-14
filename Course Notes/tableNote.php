<?php
session_start();
require '../db.php'; 

header('Content-Type: application/json; charset=utf-8');

try {
    $sql = "CREATE TABLE IF NOT EXISTS Notes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        content TEXT NOT NULL,
        course_id INT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";

    $db->exec($sql);

    echo json_encode([
        "status" => "success",
        "message" => "Table 'Notes' created successfully."
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Error creating table: " . $e->getMessage()
    ]);
}
?>
