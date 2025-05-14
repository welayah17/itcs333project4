<?php
require 'config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM reviews WHERE id = ?");
$stmt->execute([$id]);
$review = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$review) {
    die('<div class="alert alert-danger text-center">There are no comment.</div>');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($review['coursename']) ?></title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #ffcf9d;
            font-family: Arial, sans-serif;
        }
        .review-card {
            max-width: 700px;
            margin: 50px auto;
            padding: 30px;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .stars {
            font-size: 2rem;
            color: gold;
        }
        .review-header h1 {
            font-size: 2rem;
            color: #333;
        }
        .review-meta {
            font-size: 1rem;
            color: #666;
            margin-bottom: 1rem;
        }
        .review-content {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #444;
            white-space: pre-wrap;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #007bff;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="review-card">
        <div class="review-header mb-4 text-center">
            <h1><?= htmlspecialchars($review['coursename']) ?></h1>
            <p class="review-meta">
                By <?= htmlspecialchars($review['user_name']) ?><br>
                Level: <?= htmlspecialchars($review['course_level']) ?>rd Year<br>
                <small>Published on <?= date('F j, Y', strtotime($review['created_at'])) ?></small>
            </p>
        </div>

        <div class="text-center stars mb-4">
            <?= str_repeat('⭐', $review['stars']) ?>
        </div>

        <div class="review-content text-center mt-4">
            <?= htmlspecialchars($review['review']) ?: '<em>No comment provided.</em>' ?>
        </div>

        <div class="text-center mt-4">
            <a href="../courseReviews.php" class="back-link">&laquo; Back to All Reviews</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap @5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>