<?php
// config/config.php - connect to the database

try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=course_reviews;charset=utf8mb4",
        "root",
        ""
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>