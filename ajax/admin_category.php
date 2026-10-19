<?php
require_once '../config/database.php';
require_once '../models/CategoryModel.php';
require_once '../includes/session.php';
header('Content-Type: application/json');

if (!isLoggedIn() || !isAdmin()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    
    if ($action == 'add') {
        $name = $_POST['name'];
        if (addCategory($conn, $name)) {
            echo json_encode(['success' => true, 'message' => 'Category added']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add category']);
        }
    } elseif ($action == 'delete') {
        $id = $_POST['id'];
        if (deleteCategory($conn, $id)) {
            echo json_encode(['success' => true, 'message' => 'Category deleted']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete category']);
        }
    }
}
?>