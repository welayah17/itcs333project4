    <?php
    session_start();
    require '../db.php';

    header('Content-Type: application/json');

    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if (!is_array($data)) {
        echo json_encode(['success' => false, 'error' => 'Invalid JSON']);
        exit;
    }

    if (
        empty($data['id']) ||
        empty($data['name']) ||
        empty($data['category']) ||
        empty($data['description']) ||
        empty($data['leader'])
    ) {
        echo json_encode(['success' => false, 'error' => 'Missing required fields']);
        exit;
    }

    $id = (int) $data['id'];
    $name = trim($data['name']);
    $category = trim($data['category']);
    $description = trim($data['description']);
    $leader = trim($data['leader']);

    try {
        $stmt = $db->prepare("UPDATE clubs SET name = :name, category = :category, description = :description, leader = :leader WHERE id = :id");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':leader', $leader);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'Update failed: ' . $e->getMessage()]);
    }
    ?>

