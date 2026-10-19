<?php
require_once '../config/database.php';
require_once '../models/UserModel.php';
require_once '../models/CategoryModel.php';
require_once '../includes/session.php';

if (!isLoggedIn() || !isAdmin()) {
    header("Location: ../views/login.php");
    exit();
}

$action = $_GET['action'] ?? '';

if ($action == 'toggle_user') {
    $id = (int)$_GET['id'];
    $user = getUserById($conn, $id);
    if ($user && $user['id'] != $_SESSION['user_id']) {
        $newStatus = $user['status'] ? 0 : 1;
        toggleUserStatus($conn, $id, $newStatus);
    }
    header("Location: ../views/admin/users.php");
} elseif ($action == 'delete_category') {
    $id = (int)$_GET['id'];
    deleteCategory($conn, $id);
    header("Location: ../views/admin/categories.php");
} else {
    header("Location: ../views/admin/dashboard.php");
}
?>