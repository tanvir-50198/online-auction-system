<?php
require_once '../includes/session.php';
require_once '../config/database.php';
require_once '../models/BidModel.php';
require_once '../models/ListingModel.php';

if (!isLoggedIn() || !isBuyer()) {
    header("Location: ../views/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $listing_id = (int)$_POST['listing_id'];
    $bid_amount = (float)$_POST['bid_amount'];
    $buyer_id   = $_SESSION['user_id'];

    $listing = getListingById($conn, $listing_id);

    // Guard 1: auction must exist and be active
    if (!$listing || $listing['status'] != 'active' || strtotime($listing['end_datetime']) < time()) {
        header("Location: ../views/buyer/details.php?id=$listing_id&error=Auction+is+no+longer+active");
        exit();
    }

    // Guard 2: bid must exceed current bid
    if ($bid_amount <= $listing['current_bid']) {
        header("Location: ../views/buyer/details.php?id=$listing_id&error=Bid+must+be+higher+than+current+bid");
        exit();
    }

    // Guard 3: seller cannot bid on own listing
    if ($listing['seller_id'] == $buyer_id) {
        header("Location: ../views/buyer/details.php?id=$listing_id&error=You+cannot+bid+on+your+own+listing");
        exit();
    }

    if (placeBid($conn, $listing_id, $buyer_id, $bid_amount)) {
        header("Location: ../views/buyer/details.php?id=$listing_id&success=1");
    } else {
        header("Location: ../views/buyer/details.php?id=$listing_id&error=Failed+to+place+bid");
    }
    exit();
}

// Direct access without POST → redirect
header("Location: ../views/buyer/browse.php");
exit();
?>