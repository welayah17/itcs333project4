<?php 
header('Content-Type: application/json');
require '../db.php';


// Collect query parameters
$search = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? '';
$category = $_GET['category'] ?? '';
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$itemsPerPage = 6;
$offset = ($page - 1) * $itemsPerPage;

// Build WHERE clause
$where = "WHERE 1";
$params = [];

if (!empty($search)) {
    $where .= " AND (title LIKE :search OR description LIKE :search)";
    $params[':search'] = "%$search%";
}
if (!empty($category)) {
    $where .= " AND category = :category";
    $params[':category'] = $category;
}

// Sort handling
$orderBy = "ORDER BY id DESC";
switch ($sort) {
    case "p.asc": $orderBy = "ORDER BY price ASC"; break;
    case "p.desc": $orderBy = "ORDER BY price DESC"; break;
    case "t.asc": $orderBy = "ORDER BY title ASC"; break;
    case "t.desc": $orderBy = "ORDER BY title DESC"; break;
    case "d.new": $orderBy = "ORDER BY publishDate DESC"; break;
    case "d.old": $orderBy = "ORDER BY publishDate ASC"; break;
    case "popularity": $orderBy = "ORDER BY popularity DESC"; break;
}

// Total count
$countStmt = $db->prepare("SELECT COUNT(*) FROM StudentMarketplaceItem $where");
$countStmt->execute($params);
$totalItems = $countStmt->fetchColumn();
$totalPages = ceil($totalItems / $itemsPerPage);

// Fetch paginated results
$sql = "SELECT * FROM StudentMarketplaceItem $where $orderBy LIMIT :limit OFFSET :offset";
$stmt = $db->prepare($sql);
foreach ($params as $key => $val) {
    $stmt->bindValue($key, $val);
}
$stmt->bindValue(':limit', $itemsPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$listings = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Format response
$response = [
    'data' => array_map(function($listing) {
        return [
            'id' => $listing['id'],
            'title' => $listing['title'],
            'description' => $listing['description'],
            'price' => (float)$listing['price'],
            'category' => $listing['category'],
            'image' => $listing['image'],
            'publish_date' => $listing['publishDate'],
            'popularity' => $listing['popularity'],
        ];
    }, $listings),
    'pagination' => [
        'total_items' => $totalItems,
        'total_pages' => $totalPages,
        'current_page' => $page,
        'items_per_page' => $itemsPerPage
    ]
];

echo json_encode($response);
