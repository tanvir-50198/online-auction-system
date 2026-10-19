<?php
require_once '../config/database.php';
require_once '../models/ListingModel.php';
require_once '../includes/session.php';

if (!isLoggedIn() || !isSeller()) {
    header("Location: ../views/login.php");
    exit();
}

$action = $_GET['action'] ?? '';

if ($action == 'delete_listing') {
    $id = (int)$_GET['id'];
    $listing = getListingById($conn, $id);
    if ($listing && $listing['seller_id'] == $_SESSION['user_id'] && !hasBids($conn, $id)) {
        deleteListing($conn, $id);
    }
    header("Location: ../views/seller/manage_listings.php");
} else {
    header("Location: ../views/seller/dashboard.php");
}
?>