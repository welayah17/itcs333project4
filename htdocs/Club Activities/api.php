<?php
session_start();
header("Content-Type: application/json");
require '../db.php';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents("php://input"), true);

// Handle each request method
switch ($method) {
    case 'GET':
        // Fetch all clubs
        $stmt = $db->prepare("SELECT * FROM clubs");
        $stmt->execute();
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;

    case 'POST':
        // Insert new club
        if (!isset($input['name'], $input['category'], $input['description'], $input['leader'])) {
            http_response_code(400);
            echo json_encode(["error" => "Missing fields"]);
            exit;
        }

        $stmt = $db->prepare("INSERT INTO clubs (name, category, description, leader) VALUES (?, ?, ?, ?)");
        $success = $stmt->execute([
            $input['name'],
            $input['category'],
            $input['description'],
            $input['leader']
        ]);
        echo json_encode(["success" => $success]);
        break;

    case 'PUT':
        // Update existing club
        parse_str($_SERVER['QUERY_STRING'], $params);
        if (!isset($params['id']) || !isset($input['name'], $input['category'], $input['description'], $input['leader'])) {
            http_response_code(400);
            echo json_encode(["error" => "Missing ID or fields"]);
            exit;
        }

        $stmt = $db->prepare("UPDATE clubs SET name = ?, category = ?, description = ?, leader = ? WHERE id = ?");
        $success = $stmt->execute([
            $input['name'],
            $input['category'],
            $input['description'],
            $input['leader'],
            $params['id']
        ]);
        echo json_encode(["success" => $success]);
        break;

    case 'DELETE':
        // Delete a club
        parse_str($_SERVER['QUERY_STRING'], $params);
        if (!isset($params['id'])) {
            http_response_code(400);
            echo json_encode(["error" => "Missing ID"]);
            exit;
        }

        $stmt = $db->prepare("DELETE FROM clubs WHERE id = ?");
        $success = $stmt->execute([$params['id']]);
        echo json_encode(["success" => $success]);
        break;

    default:
        http_response_code(405);
        echo json_encode(["error" => "Method not allowed"]);
        break;
}
?>