<?php
require_once '../../includes/session.php';
require_once '../../config/database.php';
require_once '../../models/UserModel.php';
require_once '../../includes/functions.php';
requireRole('buyer');

$user = getUserById($conn, $_SESSION['user_id']);
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_profile'])) {
        $name = sanitize($_POST['name']);
        $phone = sanitize($_POST['phone']);
        if (updateUserProfile($conn, $_SESSION['user_id'], $name, $phone)) {
            $_SESSION['user_name'] = $name;
            $message = 'Profile updated successfully';
        } else {
            $message = 'Update failed';
        }
    } elseif (isset($_POST['change_password'])) {
        $old = $_POST['old_password'];
        $new = $_POST['new_password'];
        $confirm = $_POST['confirm_password'];
        if (password_verify($old, $user['password'])) {
            if ($new == $confirm && strlen($new) >= 6) {
                updatePassword($conn, $_SESSION['user_id'], $new);
                $message = 'Password changed';
            } else {
                $message = 'Password mismatch or too short';
            }
        } else {
            $message = 'Old password incorrect';
        }
    } elseif (isset($_FILES['profile_pic'])) {
        $file = $_FILES['profile_pic'];
        $filename = uploadFile($file, '../../uploads/profiles/');
        if ($filename && updateProfilePic($conn, $_SESSION['user_id'], $filename)) {
            $message = 'Profile picture updated';
            $user['profile_pic'] = $filename;
        } else {
            $message = $GLOBALS['upload_error'] ?: 'Image upload failed';
        }
    }
    $user = getUserById($conn, $_SESSION['user_id']);
}
?>
<?php include '../partials/header.php'; ?>
<?php include '../partials/buyer_nav.php'; ?>
<?php include '../partials/profile_view.php'; ?>
<?php include '../partials/footer.php'; ?>
