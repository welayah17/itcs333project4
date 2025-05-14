<?php
session_start();
require '../db.php';

header('Content-Type: application/json; charset=utf-8');

try {
    // Enable foreign key checks
    $db->exec("SET FOREIGN_KEY_CHECKS=1");

    // Create study_groups table
    $db->exec("
        CREATE TABLE IF NOT EXISTS study_groups (
            id INT AUTO_INCREMENT PRIMARY KEY,
            subject VARCHAR(100) NOT NULL,
            description TEXT,
            location VARCHAR(100),
            meeting_time DATETIME NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            user_id INT,
            FOREIGN KEY (user_id) REFERENCES User(id) 
                ON DELETE CASCADE 
                ON UPDATE CASCADE
        );
    ");

    echo json_encode(["message" => "study_groups table created successfully"]);
} catch (PDOException $e) {
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
?>
