<?php
function getAllCategories($conn) {
    $result = $conn->query("SELECT * FROM categories ORDER BY name");
    return $result->fetch_all(MYSQLI_ASSOC);
}

function addCategory($conn, $name) {
    $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
    $stmt->bind_param("s", $name);
    return $stmt->execute();
}

function updateCategory($conn, $id, $name) {
    $stmt = $conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
    $stmt->bind_param("si", $name, $id);
    return $stmt->execute();
}

function deleteCategory($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

function getCategoryById($conn, $id) {
    $stmt = $conn->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}
?>