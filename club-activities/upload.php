<?php
/**
 * Image Upload Handler
 * 
 * This file handles image file uploads for club activities.
 */

// Set headers for JSON response
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Check if this is a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Method not allowed'
    ]);
    exit();
}

// Check if a file was uploaded
if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'No file uploaded or upload error'
    ]);
    exit();
}

// Define upload directory
$uploadDir = 'uploads/';

// Create directory if it doesn't exist
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Generate a unique filename
$filename = uniqid() . '_' . basename($_FILES['image']['name']);
$targetFile = $uploadDir . $filename;

// Check if file is an actual image
$check = getimagesize($_FILES['image']['tmp_name']);
if ($check === false) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'File is not an image'
    ]);
    exit();
}

// Check file size (limit to 5MB)
if ($_FILES['image']['size'] > 5000000) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'File is too large (max 5MB)'
    ]);
    exit();
}

// Allow only certain file formats
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
if (!in_array($_FILES['image']['type'], $allowedTypes)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Only JPG, PNG, and GIF files are allowed'
    ]);
    exit();
}

// Try to upload the file
if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
    echo json_encode([
        'status' => 'success',
        'message' => 'File uploaded successfully',
        'data' => [
            'filename' => $filename,
            'url' => $targetFile
        ]
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to upload file'
    ]);
}
?>