<?php
require 'config.php';

$search_term = $_GET['search'] ?? '';
$level_filter = isset($_GET['level']) ? (int)$_GET['level'] : 0;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 5;
$offset = ($page - 1) * $limit;

$query = "SELECT * FROM reviews WHERE 1=1";
$count_query = "SELECT COUNT(*) FROM reviews WHERE 1=1";
$params = [];

if (!empty($search_term)) {
    $query .= " AND coursename LIKE ?";
    $count_query .= " AND coursename LIKE ?";
    $params[] = "%$search_term%";
}

if ($level_filter > 0) {
    $query .= " AND course_level = ?";
    $count_query .= " AND course_level = ?";
    $params[] = $level_filter;
}

$query .= " LIMIT $limit OFFSET $offset";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$reviews = $stmt->fetchAll();

$count_stmt = $pdo->prepare($count_query);
$count_stmt->execute(array_slice($params, 0));
$total_reviews = $count_stmt->fetchColumn();
$total_pages = ceil($total_reviews / $limit);
?>

<!-- عرض التعليقات -->
<?php if (empty($reviews)): ?>
    <p class="text-center text-muted">There is no comment.</p>
<?php else: ?>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <div class="row row-cols-1 row-cols-md-2 g-4" style="width: 600px; height: calc(auto + 20px);">
        <?php foreach ($reviews as $review): ?>
            <div class="col mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <h5><?= htmlspecialchars($review['coursename']) ?></h5>

                        <div class="startsstrap d-flex justify-content-start mb-2">
                            <div class="commentStar fs-4">
                                <?= str_repeat('⭐', $review['stars']) ?>
                            </div>
                        </div>

                        <p class="card-text"><?= nl2br(htmlspecialchars($review['review'])) ?: 'There is no comment.' ?></p>

                        <footer class="blockquote-footer mt-3">
                            <small><?= htmlspecialchars($review['user_name']) ?></small>
                            <div class="btn-group float-end mt-2">
                                <!-- Learn more Button -->
                                <a href="config/review_details.php?id=<?= $review['id'] ?>" class="btn btn-outline-info btn-sm me-2">
                                    Learn More
                                </a>

                                <!-- Edit Button -->
                                <a href="config/edit_review.php?id=<?= $review['id'] ?>" class="btn btn-outline-primary btn-sm me-2">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>

                                <!-- Delete Button -->
                                <a href="config/delete_review.php?id=<?= $review['id'] ?>" class="btn btn-outline-danger btn-sm"
                                   onclick="return confirm('Are you sure you want to delete this review?')">
                                    <i class="bi bi-trash"></i> Delete
                                </a>
                            </div>
                        </footer>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- paging -->
    <?php if ($total_pages > 1): ?>
        <nav aria-label="Page navigation" class="mt-4">
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                        <a class="page-link" href="?search=<?= urlencode($search_term) ?>&level=<?= $level_filter ?>&page=<?= $i ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
<?php endif; ?>