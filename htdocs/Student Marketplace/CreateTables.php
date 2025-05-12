<?php
session_start();
require '../db.php'; // Make sure this file sets up the $db (PDO) connection

header('Content-Type: application/json; charset=utf-8');

try {
    // Enable foreign key checks (optional but useful)
    $db->exec("SET FOREIGN_KEY_CHECKS=1");

    // 1. User Table
    $db->exec("
        CREATE TABLE IF NOT EXISTS User (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullName VARCHAR(150),
    username VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    universityEmail VARCHAR(100) UNIQUE,
    phoneNumber VARCHAR(20),
    dateOfBirth DATE,
    registrationDate DATETIME DEFAULT CURRENT_TIMESTAMP,
    studentUniversityId VARCHAR(50),
    universityName VARCHAR(100),
    image VARCHAR(255)
);
    ");

    // 2. StudentMarketplaceItem Table
    $db->exec("
    CREATE TABLE IF NOT EXISTS StudentMarketplaceItem (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    description TEXT,
    price DECIMAL(10, 2),
    category VARCHAR(100),
    status ENUM('Available', 'Sold', 'Pending') NOT NULL DEFAULT 'Available',
    phoneNumber VARCHAR(20),
    publishDate DATETIME,
    service ENUM('Student Marketplace') NOT NULL DEFAULT 'Student Marketplace',
    image VARCHAR(255),
    popularity INT DEFAULT 0,
    userId INT,
    FOREIGN KEY (userId) REFERENCES User(id) ON DELETE CASCADE
);
    ");

    // 3. StudentMarketplaceMessages Table
    $db->exec("
        CREATE TABLE IF NOT EXISTS StudentMarketplaceMessages (
            id INT AUTO_INCREMENT PRIMARY KEY,
            senderId INT,
            receiverId INT,
            itemId INT,
            title VARCHAR(255),
            body TEXT,
            sendDate DATETIME DEFAULT CURRENT_TIMESTAMP,
            status BOOLEAN DEFAULT TRUE,
            FOREIGN KEY (senderId) REFERENCES User(id) ON DELETE CASCADE,
            FOREIGN KEY (receiverId) REFERENCES User(id) ON DELETE CASCADE,
            FOREIGN KEY (itemId) REFERENCES StudentMarketplaceItem(id) ON DELETE CASCADE
        );
    ");

    // 4. StudentMarketplaceItemComments Table
    $db->exec("
        CREATE TABLE IF NOT EXISTS StudentMarketplaceItemComments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            itemId INT,
            studentId INT,
            comment TEXT,
            commentDate DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (itemId) REFERENCES StudentMarketplaceItem(id) ON DELETE CASCADE,
            FOREIGN KEY (studentId) REFERENCES User(id) ON DELETE CASCADE
        );
    ");

    echo json_encode(["message" => "All tables created successfully"]);
} catch (PDOException $e) {
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
?>

