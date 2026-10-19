<?php
require_once '../../includes/session.php';
require_once '../../config/database.php';
require_once '../../models/CategoryModel.php';
requireRole('admin');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $name = trim($_POST['name']);
    if (!empty($name)) addCategory($conn, $name);
    header("Location: categories.php");
    exit();
}
if (isset($_GET['delete'])) {
    deleteCategory($conn, (int)$_GET['delete']);
    header("Location: categories.php");
    exit();
}
$categories = getAllCategories($conn);
?>
<?php include '../partials/header.php'; ?>
<?php include '../partials/admin_nav.php'; ?>

<div class="page-head">
    <div><h1>Manage categories</h1><p>Organise auctions so buyers can find them quickly.</p></div>
</div>

<form method="POST" class="card" style="margin-bottom:24px">
    <div class="form-group" style="margin-bottom:12px">
        <label for="catName">New category</label>
        <div class="search-bar" style="margin:0">
            <input type="text" id="catName" name="name" placeholder="e.g. Electronics" style="flex:1 1 260px" required>
            <button type="submit" name="add" class="btn btn-primary"><?php echo icon('plus', 16); ?> Add category</button>
        </div>
    </div>
</form>

<?php if (empty($categories)): ?>
    <div class="empty-state"><span class="empty-icon"><?php echo icon('layers', 26); ?></span><h3>No categories yet</h3><p>Add your first category above.</p></div>
<?php else: ?>
<div class="table-wrap">
    <table class="data-table">
        <thead><tr><th>ID</th><th>Name</th><th class="right">Action</th></tr></thead>
        <tbody>
        <?php foreach ($categories as $cat): ?>
            <tr>
                <td class="muted">#<?php echo (int)$cat['id']; ?></td>
                <td class="cell-title"><?php echo e($cat['name']); ?></td>
                <td><div class="actions"><a href="categories.php?delete=<?php echo (int)$cat['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete category?')">Delete</a></div></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<script src="../../assets/js/admin.js"></script>
<?php include '../partials/footer.php'; ?>
