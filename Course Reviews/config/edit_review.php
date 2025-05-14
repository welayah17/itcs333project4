<?php
session_start();
require 'config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM reviews WHERE id = ?");
$stmt->execute([$id]);
$review = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$review) {
    $_SESSION['error'] = "التقييم غير موجود.";
    header("Location: ../courseReviews.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Review</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color: #ffcf9d; font-family: Arial, sans-serif;">
    <div class="container mt-5">
        <h1 class="text-center">Edit review:</h1>
        <div class="alert alert-info text-center">
            <strong>Edit for <?= htmlspecialchars($review['coursename']) ?></strong>
        </div>
    </div>
<div class="container mt-5">
    <h2>Edit review</h2>

    <form action="add_review.php" method="post">
        <input type="hidden" name="review_id" value="<?= $review['id'] ?>">

        <div class="mb-3">
            <label class="form-label">You'r name:</label>
            <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($review['user_name']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Name of review:</label>
            <input type="text" name="coursename" class="form-control" value="<?= htmlspecialchars($review['coursename']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Review:</label>
            <select name="stars" class="form-select" required>
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <option value="<?= $i ?>" <?= $review['stars'] == $i ? 'selected' : '' ?>>
                        <?= str_repeat('⭐', $i) ?> (<?= $i ?> Star<?= $i > 1 ? 's' : '' ?>)
                    </option>
                <?php endfor; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Course level:</label>
            <select name="course_level" class="form-select" required>
                <option value="1" <?= $review['course_level'] == 1 ? 'selected' : '' ?>>Level One</option>
                <option value="2" <?= $review['course_level'] == 2 ? 'selected' : '' ?>>Level Two</option>
                <option value="3" <?= $review['course_level'] == 3 ? 'selected' : '' ?>>Level three</option>
                <option value="4" <?= $review['course_level'] == 4 ? 'selected' : '' ?>>Level Four</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">You'r comment:</label>
            <textarea name="comment" class="form-control"><?= htmlspecialchars($review['review']) ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="../courseReviews.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap @5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>