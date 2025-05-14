<?php
session_start();
require 'config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    $_SESSION['error'] = "invalid review ID.";
    header("Location: ../courseReviews.php");
    exit;
}

$stmt = $pdo->prepare("DELETE FROM reviews WHERE id = ?");
$stmt->execute([$id]);

$_SESSION['success'] = "the review has been deleted successfully.";
header("Location: ../courseReviews.php");
exit;