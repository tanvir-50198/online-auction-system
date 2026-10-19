<?php
require_once '../config/database.php';
require_once '../includes/session.php';
require_once '../models/ListingModel.php';
require_once '../models/BidModel.php';
header('Content-Type: application/json');

if (!isLoggedIn() || !isSeller()) {
    echo json_encode([]);
    exit();
}

$seller_id = $_SESSION['user_id'];
$listings = getSellerListings($conn, $seller_id);

$updates = [];
foreach ($listings as $listing) {
    $updates[] = [
        'id' => $listing['id'],
        'title' => $listing['title'],
        'current_bid' => $listing['current_bid'],
        'bid_count' => $listing['bid_count'],
        'status' => $listing['status']
    ];
}

echo json_encode($updates);
?>