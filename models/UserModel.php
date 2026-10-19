<?php
function createUser($conn, $name, $email, $phone, $password, $role) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (name, email, phone, password, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $phone, $hashedPassword, $role);
    return $stmt->execute();
}

function getUserByEmail($conn, $email) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function getUserById($conn, $id) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function updateUserProfile($conn, $id, $name, $phone) {
    $stmt = $conn->prepare("UPDATE users SET name = ?, phone = ? WHERE id = ?");
    $stmt->bind_param("ssi", $name, $phone, $id);
    return $stmt->execute();
}

function updatePassword($conn, $id, $newPassword) {
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $hashedPassword, $id);
    return $stmt->execute();
}

function updateProfilePic($conn, $id, $filename) {
    $stmt = $conn->prepare("UPDATE users SET profile_pic = ? WHERE id = ?");
    $stmt->bind_param("si", $filename, $id);
    return $stmt->execute();
}

function toggleUserStatus($conn, $id, $status) {
    $stmt = $conn->prepare("UPDATE users SET status = ? WHERE id = ?");
    $stmt->bind_param("ii", $status, $id);
    return $stmt->execute();
}

function getAllUsers($conn, $role = null) {
    if ($role) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE role = ? ORDER BY created_at DESC");
        $stmt->bind_param("s", $role);
    } else {
        $stmt = $conn->prepare("SELECT * FROM users ORDER BY created_at DESC");
    }
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getTotalUsers($conn) {
    $result = $conn->query("SELECT COUNT(*) as total FROM users");
    return $result->fetch_assoc()['total'];
}
?>