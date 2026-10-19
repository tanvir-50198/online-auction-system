<?php
require_once '../config/database.php';
require_once '../models/ListingModel.php';
require_once '../includes/session.php';
require_once '../includes/functions.php';
if (!isLoggedIn() || !isBuyer()) {
    echo json_encode([]);
    exit();
}

header('Content-Type: application/json');
$category = isset($_GET['category']) ? (int)$_GET['category'] : null;
$search = isset($_GET['search']) && $_GET['search'] !== '' ? $conn->real_escape_string($_GET['search']) : null;

$listings = getActiveListings($conn, null, $category, $search);
foreach ($listings as &$l) {
    $l['image_url'] = listingImageUrl($l['image']);
    $l['title'] = e($l['title']);
    $l['category_name'] = e($l['category_name']);
}
unset($l);
echo json_encode($listings);
?>