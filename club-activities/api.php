<?php
/**
 * REST API for Club Activities
 * 
 * This file provides API endpoints to interact with club activities data.
 */

// Set headers for JSON API
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Include the DatabaseHelper class
require_once 'DatabaseHelper.php';

// Database configuration
$db_host = 'localhost';
$db_name = getenv('DB_NAME') ?: 'campus_hub';
$db_user = getenv('DB_USER') ?: 'root';
$db_pass = getenv('DB_PASS') ?: '';

// Create database helper instance
$dbHelper = new DatabaseHelper($db_host, $db_name, $db_user, $db_pass);

// Get action parameter
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Process the request based on action
try {
    switch ($action) {
        // Get all activities
        case 'getActivities':
            // Get query parameters
            $searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
            $club = isset($_GET['club']) ? trim($_GET['club']) : '';
            $sortBy = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;

            // Get activities
            $result = $dbHelper->getActivities($searchTerm, $club, $sortBy, $page, $limit);

            // Return response
            echo json_encode([
                'status' => 'success',
                'data' => $result['activities'],
                'meta' => [
                    'total' => $result['total'],
                    'page' => $result['page'],
                    'limit' => $result['limit'],
                    'totalPages' => $result['totalPages']
                ]
            ]);
            break;

        // Get a single activity
        case 'getActivity':
            $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

            if ($id <= 0) {
                throw new Exception('Invalid activity ID');
            }

            $activity = $dbHelper->getActivity($id);

            if (!$activity) {
                throw new Exception('Activity not found');
            }

            // Get comments for this activity
            $activity['comments'] = $dbHelper->getComments($id);

            echo json_encode([
                'status' => 'success',
                'data' => $activity
            ]);
            break;

        // Create a new activity
        case 'createActivity':
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method');
            }

            // Get JSON data
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);

            if (!$data) {
                throw new Exception('Invalid data format');
            }

            // Validate required fields
            if (empty($data['title']) || empty($data['club']) || empty($data['content']) || empty($data['datetime'])) {
                throw new Exception('All fields are required');
            }

            // Create activity
            $activityId = $dbHelper->createActivity(
                $data['title'],
                $data['club'],
                $data['content'],
                $data['datetime'],
                isset($data['image_url']) ? $data['image_url'] : null
            );

            if (!$activityId) {
                throw new Exception('Failed to create activity');
            }

            // Get the created activity
            $activity = $dbHelper->getActivity($activityId);

            echo json_encode([
                'status' => 'success',
                'message' => 'Activity created successfully',
                'data' => $activity
            ]);
            break;

        // Update an existing activity
        case 'updateActivity':
            if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'PUT') {
                throw new Exception('Invalid request method');
            }

            // Get JSON data
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);

            if (!$data) {
                throw new Exception('Invalid data format');
            }

            // Validate required fields
            if (empty($data['id']) || empty($data['title']) || empty($data['club']) || empty($data['content']) || empty($data['datetime'])) {
                throw new Exception('All fields are required');
            }

            // Check if activity exists
            $activity = $dbHelper->getActivity($data['id']);

            if (!$activity) {
                throw new Exception('Activity not found');
            }

            // Update activity
            $success = $dbHelper->updateActivity(
                $data['id'],
                $data['title'],
                $data['club'],
                $data['content'],
                $data['datetime'],
                isset($data['image_url']) ? $data['image_url'] : null
            );

            if (!$success) {
                throw new Exception('Failed to update activity');
            }

            // Get the updated activity
            $activity = $dbHelper->getActivity($data['id']);

            echo json_encode([
                'status' => 'success',
                'message' => 'Activity updated successfully',
                'data' => $activity
            ]);
            break;

        // Delete an activity
        case 'deleteActivity':
            if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'DELETE') {
                throw new Exception('Invalid request method');
            }

            // Get activity ID from JSON data or query parameter
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $json = file_get_contents('php://input');
                $data = json_decode($json, true);
                $id = isset($data['id']) ? (int)$data['id'] : 0;
            } else {
                $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
            }

            if ($id <= 0) {
                throw new Exception('Invalid activity ID');
            }

            // Check if activity exists
            $activity = $dbHelper->getActivity($id);

            if (!$activity) {
                throw new Exception('Activity not found');
            }

            // Delete activity
            $success = $dbHelper->deleteActivity($id);

            if (!$success) {
                throw new Exception('Failed to delete activity');
            }

            echo json_encode([
                'status' => 'success',
                'message' => 'Activity deleted successfully'
            ]);
            break;

        // Get comments for an activity
        case 'getComments':
            $activityId = isset($_GET['activity_id']) ? (int)$_GET['activity_id'] : 0;

            if ($activityId <= 0) {
                throw new Exception('Invalid activity ID');
            }

            // Get comments
            $comments = $dbHelper->getComments($activityId);

            echo json_encode([
                'status' => 'success',
                'data' => $comments
            ]);
            break;

        // Add a comment to an activity
        case 'addComment':
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method');
            }

            // Get JSON data
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);

            if (!$data) {
                throw new Exception('Invalid data format');
            }

            // Validate required fields
            if (empty($data['activity_id']) || empty($data['name']) || empty($data['comment'])) {
                throw new Exception('All fields are required');
            }

            // Check if activity exists
            $activity = $dbHelper->getActivity($data['activity_id']);

            if (!$activity) {
                throw new Exception('Activity not found');
            }

            // Add comment
            $commentId = $dbHelper->addComment(
                $data['activity_id'],
                $data['name'],
                $data['comment']
            );

            if (!$commentId) {
                throw new Exception('Failed to add comment');
            }

            // Get all comments for this activity
            $comments = $dbHelper->getComments($data['activity_id']);

            echo json_encode([
                'status' => 'success',
                'message' => 'Comment added successfully',
                'data' => $comments
            ]);
            break;

        // Delete a comment
        case 'deleteComment':
            if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'DELETE') {
                throw new Exception('Invalid request method');
            }

            // Get comment ID from JSON data or query parameter
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $json = file_get_contents('php://input');
                $data = json_decode($json, true);
                $id = isset($data['id']) ? (int)$data['id'] : 0;
            } else {
                $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
            }

            if ($id <= 0) {
                throw new Exception('Invalid comment ID');
            }

            // Delete comment
            $success = $dbHelper->deleteComment($id);

            if (!$success) {
                throw new Exception('Failed to delete comment');
            }

            echo json_encode([
                'status' => 'success',
                'message' => 'Comment deleted successfully'
            ]);
            break;

        default:
            throw new Exception('Invalid action');
    }
} catch (Exception $e) {
    // Return error response
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>