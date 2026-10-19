<?php
require_once '../../includes/session.php';
require_once '../../config/database.php';
require_once '../../models/UserModel.php';
require_once '../../includes/functions.php';
requireRole('admin');

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];
    
    if (empty($name) || empty($email) || empty($password)) {
        $message = 'All fields are required';
    } elseif (!validateEmail($email)) {
        $message = 'Invalid email';
    } elseif (strlen($password) < 6) {
        $message = 'Password must be at least 6 characters';
    } elseif ($password != $confirm) {
        $message = 'Passwords do not match';
    } elseif (getUserByEmail($conn, $email)) {
        $message = 'Email already exists';
    } else {
        if (createUser($conn, $name, $email, '0000000000', $password, 'admin')) {
            $message = 'Admin created successfully';
        } else {
            $message = 'Creation failed';
        }
    }
}
?>
<?php include '../partials/header.php'; ?>
<?php include '../partials/admin_nav.php'; ?>

<div class="page-head">
    <div><h1>Create new admin</h1><p>Admins have full access to users, listings and categories.</p></div>
</div>

<?php if ($message): ?>
    <div class="alert <?php echo $message === 'Admin created successfully' ? 'alert-success' : 'alert-error'; ?>" data-persist><?php echo e($message); ?></div>
<?php endif; ?>

<form method="POST" class="form-card">
    <div class="form-group"><label for="name">Full name</label><input type="text" id="name" name="name" required></div>
    <div class="form-group"><label for="email">Email</label><input type="email" id="email" name="email" required></div>
    <div class="form-row">
        <div class="form-group"><label for="password">Password</label><input type="password" id="password" name="password" minlength="6" required></div>
        <div class="form-group"><label for="confirm_password">Confirm password</label><input type="password" id="confirm_password" name="confirm_password" minlength="6" required></div>
    </div>
    <div class="form-actions"><button type="submit" class="btn btn-primary btn-lg">Create admin</button></div>
</form>

<?php include '../partials/footer.php'; ?>
