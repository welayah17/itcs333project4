<?php
// api/reviews.php - Simple RESTful API for Course Reviews

header("Content-Type: application/json");

require '../config/config.php'; // اتصال قاعدة البيانات

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        getReviews();
        break;
    case 'POST':
        addReview();
        break;
    case 'PUT':
        updateReview();
        break;
    case 'DELETE':
        deleteReview();
        break;
    default:
        http_response_code(405); // Method Not Allowed
        echo json_encode(['error' => 'Method not allowed']);
}

function getReviews() {
    global $pdo;

    $search = $_GET['search'] ?? '';
    $level = $_GET['level'] ?? '';
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = 5;
    $offset = ($page - 1) * $limit;

    $query = "SELECT * FROM reviews WHERE 1=1";
    $params = [];

    if (!empty($search)) {
        $query .= " AND coursename LIKE ?";
        $params[] = "%$search%";
    }

    if (!empty($level)) {
        $query .= " AND course_level = ?";
        $params[] = $level;
    }

    $query .= " LIMIT $limit OFFSET $offset";

    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // جلب العدد الإجمالي للتعليقات
        $countQuery = str_replace("LIMIT $limit OFFSET $offset", "", $query);
        $countStmt = $pdo->prepare($countQuery);
        $countStmt->execute(array_slice($params, 0));
        $total = $countStmt->rowCount();

        echo json_encode([
            'reviews' => $reviews,
            'total' => $total,
            'page' => $page,
            'pages' => ceil($total / $limit)
        ]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

function addReview() {
    global $pdo;

    $data = json_decode(file_get_contents('php://input'), true);

    if (
        empty($data['username']) ||
        empty($data['coursename']) ||
        !isset($data['stars']) ||
        !isset($data['course_level'])
    ) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        return;
    }

    $username = htmlspecialchars(strip_tags($data['username']));
    $coursename = htmlspecialchars(strip_tags($data['coursename']));
    $stars = (int)$data['stars'];
    $course_level = (int)$data['course_level'];
    $review = htmlspecialchars(strip_tags($data['comment'] ?? ''));

    if ($stars < 1 || $stars > 5) {
        http_response_code(400);
        echo json_encode(['error' => 'Stars must be between 1 and 5']);
        return;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO reviews (user_name, coursename, stars, review, course_level) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$username, $coursename, $stars, $review, $course_level]);

        http_response_code(201);
        echo json_encode(['message' => 'Review added successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

function updateReview() {
    global $pdo;

    $id = $_GET['id'] ?? null;

    if (!$id || !is_numeric($id)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid or missing ID']);
        return;
    }

    $data = json_decode(file_get_contents('php://input'), true);

    if (
        empty($data['username']) ||
        empty($data['coursename']) ||
        !isset($data['stars']) ||
        !isset($data['course_level'])
    ) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        return;
    }

    $username = htmlspecialchars(strip_tags($data['username']));
    $coursename = htmlspecialchars(strip_tags($data['coursename']));
    $stars = (int)$data['stars'];
    $course_level = (int)$data['course_level'];
    $review = htmlspecialchars(strip_tags($data['comment'] ?? ''));

    if ($stars < 1 || $stars > 5) {
        http_response_code(400);
        echo json_encode(['error' => 'Stars must be between 1 and 5']);
        return;
    }

    try {
        $stmt = $pdo->prepare("UPDATE reviews SET user_name=?, coursename=?, stars=?, review=?, course_level=? WHERE id=?");
        $stmt->execute([$username, $coursename, $stars, $review, $course_level, $id]);

        echo json_encode(['message' => 'Review updated successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

function deleteReview() {
    global $pdo;

    $id = $_GET['id'] ?? null;

    if (!$id || !is_numeric($id)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid or missing ID']);
        return;
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM reviews WHERE id = ?");
        $stmt->execute([$id]);

        echo json_encode(['message' => 'Review deleted successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}