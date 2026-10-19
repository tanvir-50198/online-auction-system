<?php
require_once '../config/database.php';
require_once '../models/UserModel.php';
require_once '../includes/session.php';
require_once '../includes/functions.php';

$action = $_GET['action'] ?? '';

if ($action == 'login') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    $user = getUserByEmail($conn, $email);
    
    if ($user && password_verify($password, $user['password'])) {
        if ($user['status'] == 0) {
            header("Location: ../views/login.php?error=Account deactivated");
            exit();
        }
        
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        
        if ($user['role'] == 'buyer') {
            header("Location: ../views/buyer/dashboard.php");
        } elseif ($user['role'] == 'seller') {
            header("Location: ../views/seller/dashboard.php");
        } else {
            header("Location: ../views/admin/dashboard.php");
        }
        exit();
    } else {
        header("Location: ../views/login.php?error=1&email=" . urlencode($email));
        exit();
    }
} elseif ($action == 'register') {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];
    $role = $_POST['role'] ?? 'buyer';
    $role = in_array($role, ['buyer', 'seller'], true) ? $role : 'buyer';
    
    // Server validation
    $queryString = sprintf('name=%s&email=%s&phone=%s&role=%s',
        urlencode($name),
        urlencode($email),
        urlencode($phone),
        urlencode($role)
    );

    if (empty($name) || empty($email) || empty($phone) || empty($password)) {
        header("Location: ../views/register.php?error=All fields required&$queryString");
        exit();
    }
    
    if (!validateEmail($email)) {
        header("Location: ../views/register.php?error=Invalid email format&$queryString");
        exit();
    }
    
    if (!validatePhone($phone)) {
        header("Location: ../views/register.php?error=Invalid phone number&$queryString");
        exit();
    }
    
    if (strlen($password) < 6) {
        header("Location: ../views/register.php?error=Password must be at least 6 characters&$queryString");
        exit();
    }
    
    if ($password !== $confirm) {
        header("Location: ../views/register.php?error=Passwords do not match&$queryString");
        exit();
    }
    
    // Check if email exists
    if (getUserByEmail($conn, $email)) {
        header("Location: ../views/register.php?error=Email already registered&$queryString");
        exit();
    }
    
    if (createUser($conn, $name, $email, $phone, $password, $role)) {
        header("Location: ../views/login.php?registered=1");
        exit();
    } else {
        header("Location: ../views/register.php?error=Registration failed&$queryString");
        exit();
    }
} elseif ($action == 'logout') {
    session_destroy();
    header("Location: ../views/login.php?logout=1");
    exit();
}
?>