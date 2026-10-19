<?php
function placeBid($conn, $listing_id, $buyer_id, $bid_amount) {
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Insert bid
        $stmt = $conn->prepare("INSERT INTO bids (listing_id, buyer_id, bid_amount) VALUES (?, ?, ?)");
        $stmt->bind_param("iid", $listing_id, $buyer_id, $bid_amount);
        $stmt->execute();
        
        // Update listing current bid
        $stmt2 = $conn->prepare("UPDATE listings SET current_bid = ? WHERE id = ?");
        $stmt2->bind_param("di", $bid_amount, $listing_id);
        $stmt2->execute();
        
        $conn->commit();
        return true;
    } catch (Exception $e) {
        $conn->rollback();
        return false;
    }
}

function getHighestBid($conn, $listing_id) {
    $stmt = $conn->prepare("SELECT MAX(bid_amount) as max_bid FROM bids WHERE listing_id = ?");
    $stmt->bind_param("i", $listing_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result['max_bid'] ?? 0;
}

function getBidHistory($conn, $listing_id) {
    $stmt = $conn->prepare("SELECT b.*, u.name as buyer_name FROM bids b JOIN users u ON b.buyer_id = u.id WHERE b.listing_id = ? ORDER BY b.bid_amount DESC");
    $stmt->bind_param("i", $listing_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getUserBids($conn, $user_id) {
    $stmt = $conn->prepare("SELECT 
                            b.listing_id,
                            l.title, l.end_datetime, l.status, l.image,
                            MAX(b.bid_amount) as bid_amount,
                            (SELECT MAX(bid_amount) FROM bids WHERE listing_id = l.id) as highest_bid,
                            (SELECT buyer_id FROM bids WHERE listing_id = l.id ORDER BY bid_amount DESC LIMIT 1) as winner_id,
                            MAX(b.created_at) as created_at
                            FROM bids b 
                            JOIN listings l ON b.listing_id = l.id 
                            WHERE b.buyer_id = ? 
                            GROUP BY b.listing_id, l.title, l.end_datetime, l.status, l.image
                            ORDER BY MAX(b.created_at) DESC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getTotalBids($conn) {
    $result = $conn->query("SELECT COUNT(*) as total FROM bids");
    return $result->fetch_assoc()['total'];
}

function getListingBidsCount($conn, $listing_id) {
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM bids WHERE listing_id = ?");
    $stmt->bind_param("i", $listing_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result['count'];
}
?>