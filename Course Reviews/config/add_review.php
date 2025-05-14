<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $coursename = trim($_POST['coursename']);
    $stars = (int)$_POST['stars'];
    $comment = trim($_POST['comment'] ?? '');
    $course_level = (int)$_POST['course_level'];

    if (empty($username) || empty($coursename) || $stars < 1 || $stars > 5) {
        $_SESSION['error'] = "Please fill in all fields correctly.";
        header("Location: ../courseReviews.php");
        exit;
    }

    try {
        if (!empty($_POST['review_id'])) {
            // Update Evaluation
            $stmt = $pdo->prepare("UPDATE reviews SET user_name=?, coursename=?, stars=?, review=?, course_level=? WHERE id=?");
            $stmt->execute([$username, $coursename, $stars, $comment, $course_level, $_POST['review_id']]);
        } else {
            // Add a new rating
            $stmt = $pdo->prepare("INSERT INTO reviews (user_name, coursename, stars, review, course_level) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$username, $coursename, $stars, $comment, $course_level]);
        }

        $_SESSION['success'] = "The rating was saved successfully.";
    } catch (PDOException $e) {
        $_SESSION['error'] = "An error occurred while saving.";
    }
}

header("Location: ../courseReviews.php");
exit;