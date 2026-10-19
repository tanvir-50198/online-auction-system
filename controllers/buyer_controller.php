<?php
// Handles buyer specific actions (e.g., add review, remove watchlist)
require_once '../config/database.php';
require_once '../models/WatchlistModel.php';
require_once '../models/ReviewModel.php';
require_once '../includes/session.php';

if (!isLoggedIn() || !isBuyer()) {
    header("Location: ../views/login.php");
    exit();
}

$action = $_GET['action'] ?? '';

if ($action == 'remove_watchlist') {
    $listing_id = (int)$_GET['listing_id'];
    removeFromWatchlist($conn, $_SESSION['user_id'], $listing_id);
    header("Location: ../views/buyer/watchlist.php");
} elseif ($action == 'add_review') {
    // handled in view, but can be here
    header("Location: ../views/buyer/add_review.php?listing_id=" . (int)$_GET['listing_id']);
} else {
    header("Location: ../views/buyer/dashboard.php");
}
?>