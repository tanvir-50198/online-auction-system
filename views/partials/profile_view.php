<?php
/* Shared profile UI. Expects $user (array) and $message (string). */
$isSuccess = ($message !== '') && (bool)preg_match('/updated|changed/i', $message);
?>
<div class="page-head">
    <div><h1>My profile</h1><p>Manage your personal details, photo and password.</p></div>
</div>

<?php if ($message !== ''): ?>
    <div class="alert <?php echo $isSuccess ? 'alert-success' : 'alert-error'; ?>"><?php echo e($message); ?></div>
<?php endif; ?>

<div class="profile-layout">
    <aside class="card profile-summary">
        <img class="profile-photo" src="<?php echo profileImageUrl($user['profile_pic']); ?>" alt="Profile photo">
        <h2><?php echo e($user['name']); ?></h2>
        <p class="muted"><?php echo e($user['email']); ?></p>
        <span class="badge role-<?php echo e($user['role']); ?>"><?php echo e(ucfirst($user['role'])); ?></span>

        <form method="POST" enctype="multipart/form-data" class="upload-form">
            <label for="profilePic" style="font-weight:600;font-size:.86rem;color:var(--text-2)">Change photo <small class="muted">(max 5 MB)</small></label>
            <input type="file" id="profilePic" name="profile_pic" accept="image/jpeg,image/png,image/gif,image/webp" required>
            <button type="submit" class="btn btn-primary btn-sm btn-block">Upload photo</button>
        </form>
    </aside>

    <div class="profile-forms">
        <form method="POST" class="card">
            <div class="card-title"><h3>Personal details</h3></div>
            <div class="form-group"><label for="pname">Full name</label><input type="text" id="pname" name="name" value="<?php echo e($user['name']); ?>" required></div>
            <div class="form-row">
                <div class="form-group"><label for="pemail">Email</label><input type="email" id="pemail" value="<?php echo e($user['email']); ?>" disabled></div>
                <div class="form-group"><label for="pphone">Phone</label><input type="text" id="pphone" name="phone" value="<?php echo e($user['phone']); ?>" required></div>
            </div>
            <button type="submit" name="update_profile" class="btn btn-primary">Save changes</button>
        </form>

        <form method="POST" class="card">
            <div class="card-title"><h3>Change password</h3></div>
            <div class="form-group"><label for="old_password">Current password</label><input type="password" id="old_password" name="old_password" required></div>
            <div class="form-row">
                <div class="form-group"><label for="new_password">New password <small>(min 6)</small></label><input type="password" id="new_password" name="new_password" minlength="6" required></div>
                <div class="form-group"><label for="confirm_password">Confirm new password</label><input type="password" id="confirm_password" name="confirm_password" minlength="6" required></div>
            </div>
            <button type="submit" name="change_password" class="btn btn-primary">Update password</button>
        </form>
    </div>
</div>
