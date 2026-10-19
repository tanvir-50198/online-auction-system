<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isBuyer() {
    return isset($_SESSION['role']) && $_SESSION['role'] == 'buyer';
}

function isSeller() {
    return isset($_SESSION['role']) && $_SESSION['role'] == 'seller';
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] == 'admin';
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: ../views/login.php");
        exit();
    }
}

function requireRole($role) {
    requireLogin();
    if ($_SESSION['role'] != $role) {
        header("Location: ../index.php");
        exit();
    }
}
?>