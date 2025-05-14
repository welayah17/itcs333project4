<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$host = "YOUR_MYSQL_HOST";
$dbname = "CampusHub";
$charset = "utf8";
$user = "Group4";
$pass = "G4";

$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

$method = $_SERVER['REQUEST_METHOD'];
$uri = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));
$id = isset($uri[1]) && is_numeric($uri[1]) ? intval($uri[1]) : null;

function isValid($data) {
    return isset($data['name'], $data['instructor'], $data['rating'], $data['review'], $data['college']) &&
           is_string($data['name']) &&
           is_string($data['instructor']) &&
           is_numeric($data['rating']) &&
           $data['rating'] >= 1 && $data['rating'] <= 5 &&
           is_string($data['review']) &&
           is_string($data['college']);
}

switch ($method) {
    case 'GET':
        if ($id) {
            $stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
        } else {
            $stmt = $pdo->query("SELECT * FROM courses");
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        if (isValid($data)) {
            $stmt = $pdo->prepare("INSERT INTO courses (name, instructor, rating, review, college) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $data['name'], $data['instructor'], $data['rating'], $data['review'], $data['college']
            ]);
            echo json_encode(["message" => "Course added"]);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Invalid input"]);
        }
        break;

    case 'PUT':
        if ($id) {
            $data = json_decode(file_get_contents("php://input"), true);
            if (isValid($data)) {
                $stmt = $pdo->prepare("UPDATE courses SET name=?, instructor=?, rating=?, review=?, college=? WHERE id=?");
                $stmt->execute([
                    $data['name'], $data['instructor'], $data['rating'], $data['review'], $data['college'], $id
                ]);
                echo json_encode(["message" => "Course updated"]);
            } else {
                http_response_code(400);
                echo json_encode(["error" => "Invalid input"]);
            }
        }
        break;

    case 'DELETE':
        if ($id) {
            $stmt = $pdo->prepare("DELETE FROM courses WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(["message" => "Course deleted"]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["error" => "Method not allowed"]);
}
?>
