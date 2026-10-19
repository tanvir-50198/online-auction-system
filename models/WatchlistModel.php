<?php
function addToWatchlist($conn, $buyer_id, $listing_id) {
    $stmt = $conn->prepare("INSERT INTO watchlist (buyer_id, listing_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $buyer_id, $listing_id);
    return $stmt->execute();
}

function removeFromWatchlist($conn, $buyer_id, $listing_id) {
    $stmt = $conn->prepare("DELETE FROM watchlist WHERE buyer_id = ? AND listing_id = ?");
    $stmt->bind_param("ii", $buyer_id, $listing_id);
    return $stmt->execute();
}

function isInWatchlist($conn, $buyer_id, $listing_id) {
    $stmt = $conn->prepare("SELECT id FROM watchlist WHERE buyer_id = ? AND listing_id = ?");
    $stmt->bind_param("ii", $buyer_id, $listing_id);
    $stmt->execute();
    return $stmt->get_result()->num_rows > 0;
}

function getWatchlist($conn, $buyer_id) {
    $stmt = $conn->prepare("SELECT l.*, c.name as category_name FROM watchlist w 
                            JOIN listings l ON w.listing_id = l.id 
                            JOIN categories c ON l.category_id = c.id 
                            WHERE w.buyer_id = ? AND l.status = 'active'
                            ORDER BY w.created_at DESC");
    $stmt->bind_param("i", $buyer_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
?>