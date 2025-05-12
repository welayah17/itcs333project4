<?php
session_start();
require '../db.php';

header('Content-Type: application/json; charset=utf-8');

try {
    // Enable foreign key checks
    $db->exec("SET FOREIGN_KEY_CHECKS=1");

    // Create Clubs table
    $db->exec("
      CREATE TABLE clubs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  category VARCHAR(100),
  description TEXT,
  leader VARCHAR(100),
  user_id INT,
  FOREIGN KEY (user_id) REFERENCES User(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

    ");

    echo json_encode(["message" => "Clubs table created successfully"]);
} catch (PDOException $e) {
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
?>
