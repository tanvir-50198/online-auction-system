<?php
session_start();

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] == 'buyer') {
        header("Location: views/buyer/dashboard.php");
    } elseif ($_SESSION['role'] == 'seller') {
        header("Location: views/seller/dashboard.php");
    } else {
        header("Location: views/admin/dashboard.php");
    }
} else {
    header("Location: views/login.php");
}
?>