<?php
require_once '../../includes/session.php';
require_once '../../config/database.php';
require_once '../../models/UserModel.php';
requireRole('admin');

$users = getAllUsers($conn);
if (isset($_GET['toggle'])) {
    $id = (int)$_GET['toggle'];
    $user = getUserById($conn, $id);
    if ($user && $user['id'] != $_SESSION['user_id']) {
        $newStatus = $user['status'] ? 0 : 1;
        toggleUserStatus($conn, $id, $newStatus);
    }
    header("Location: users.php");
    exit();
}
?>
<?php include '../partials/header.php'; ?>
<?php include '../partials/admin_nav.php'; ?>

<div class="page-head">
    <div><h1>Manage users</h1><p><?php echo count($users); ?> registered account<?php echo count($users) === 1 ? '' : 's'; ?></p></div>
</div>

<div class="table-wrap">
    <table class="data-table">
        <thead><tr><th>User</th><th>Role</th><th>Status</th><th class="right">Action</th></tr></thead>
        <tbody>
        <?php foreach ($users as $u): ?>
            <tr>
                <td>
                    <div class="cell-user">
                        <span class="avatar"><?php echo e(strtoupper(substr($u['name'], 0, 1))); ?></span>
                        <div>
                            <div class="cell-title"><?php echo e($u['name']); ?> <span class="muted">#<?php echo (int)$u['id']; ?></span></div>
                            <div class="cell-sub"><?php echo e($u['email']); ?></div>
                        </div>
                    </div>
                </td>
                <td><span class="badge role-<?php echo e($u['role']); ?>"><?php echo e(ucfirst($u['role'])); ?></span></td>
                <td><?php echo $u['status'] ? statusBadge('active', 'Active') : statusBadge('deactivated', 'Deactivated'); ?></td>
                <td>
                    <div class="actions">
                    <?php if ($u['id'] != $_SESSION['user_id']): ?>
                        <a href="users.php?toggle=<?php echo (int)$u['id']; ?>" class="btn btn-sm <?php echo $u['status'] ? 'btn-danger' : ''; ?>"><?php echo $u['status'] ? 'Deactivate' : 'Activate'; ?></a>
                    <?php else: ?>
                        <span class="muted">You</span>
                    <?php endif; ?>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../partials/footer.php'; ?>
