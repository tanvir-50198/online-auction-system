<?php
function createListing($conn, $seller_id, $category_id, $title, $description, $starting_price, $end_datetime, $image) {
    $stmt = $conn->prepare("INSERT INTO listings (seller_id, category_id, title, description, starting_price, current_bid, end_datetime, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iisssdss", $seller_id, $category_id, $title, $description, $starting_price, $starting_price, $end_datetime, $image);
    return $stmt->execute();
}

function getListingById($conn, $id) {
    $stmt = $conn->prepare("SELECT l.*, c.name as category_name, u.name as seller_name FROM listings l JOIN categories c ON l.category_id = c.id JOIN users u ON l.seller_id = u.id WHERE l.id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function getActiveListings($conn, $limit = null, $category = null, $search = null) {
    $sql = "SELECT l.*, c.name as category_name, COUNT(b.id) as bid_count FROM listings l 
            JOIN categories c ON l.category_id = c.id 
            LEFT JOIN bids b ON l.id = b.listing_id 
            WHERE l.status = 'active' AND l.end_datetime > NOW()";
    
    if ($category) {
        $sql .= " AND l.category_id = $category";
    }
    if ($search) {
        $sql .= " AND (l.title LIKE '%$search%' OR l.description LIKE '%$search%')";
    }
    
    $sql .= " GROUP BY l.id ORDER BY l.created_at DESC";
    
    if ($limit) {
        $sql .= " LIMIT $limit";
    }
    
    return $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
}

function getSellerListings($conn, $seller_id) {
    $stmt = $conn->prepare("SELECT l.*, c.name as category_name, COUNT(b.id) as bid_count, MAX(b.bid_amount) as highest_bid FROM listings l JOIN categories c ON l.category_id = c.id LEFT JOIN bids b ON l.id = b.listing_id WHERE l.seller_id = ? GROUP BY l.id ORDER BY l.created_at DESC");
    $stmt->bind_param("i", $seller_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function updateListing($conn, $id, $title, $description, $category_id, $end_datetime) {
    $stmt = $conn->prepare("UPDATE listings SET title = ?, description = ?, category_id = ?, end_datetime = ? WHERE id = ?");
    $stmt->bind_param("ssisi", $title, $description, $category_id, $end_datetime, $id);
    return $stmt->execute();
}

function updateListingImage($conn, $id, $image) {
    $stmt = $conn->prepare("UPDATE listings SET image = ? WHERE id = ?");
    $stmt->bind_param("si", $image, $id);
    return $stmt->execute();
}

function deleteListing($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM listings WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

function hasBids($conn, $listing_id) {
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM bids WHERE listing_id = ?");
    $stmt->bind_param("i", $listing_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result['count'] > 0;
}

function closeExpiredListings($conn) {
    $conn->query("UPDATE listings SET status = 'closed' WHERE end_datetime <= NOW() AND status = 'active'");
}

function getAllListings($conn) {
    $stmt = $conn->prepare("SELECT l.*, u.name as seller_name, c.name as category_name FROM listings l JOIN users u ON l.seller_id = u.id JOIN categories c ON l.category_id = c.id ORDER BY l.created_at DESC");
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getTotalListings($conn) {
    $result = $conn->query("SELECT COUNT(*) as total FROM listings");
    return $result->fetch_assoc()['total'];
}
?>