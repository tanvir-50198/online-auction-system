<?php
require_once '../../includes/session.php';
require_once '../../config/database.php';
require_once '../../models/ListingModel.php';
requireRole('admin');

if (isset($_GET['delete'])) {
    deleteListing($conn, (int)$_GET['delete']);
    header("Location: listings.php");
    exit();
}
$listings = getAllListings($conn);
?>
<?php include '../partials/header.php'; ?>
<?php include '../partials/admin_nav.php'; ?>

<div class="page-head">
    <div><h1>All listings</h1><p><?php echo count($listings); ?> listing<?php echo count($listings) === 1 ? '' : 's'; ?> on the platform</p></div>
</div>

<?php if (empty($listings)): ?>
    <div class="empty-state"><span class="empty-icon"><?php echo icon('package', 26); ?></span><h3>No listings yet</h3><p>Listings created by sellers will appear here.</p></div>
<?php else: ?>
<div class="table-wrap">
    <table class="data-table">
        <thead><tr><th>Item</th><th>Seller</th><th>Category</th><th>Current bid</th><th>Status</th><th class="right">Action</th></tr></thead>
        <tbody>
        <?php foreach ($listings as $l): ?>
            <tr>
                <td>
                    <div class="cell-user">
                        <img class="thumb" src="<?php echo listingImageUrl($l['image']); ?>" alt="">
                        <div><div class="cell-title"><?php echo e($l['title']); ?></div><div class="cell-sub">#<?php echo (int)$l['id']; ?></div></div>
                    </div>
                </td>
                <td><?php echo e($l['seller_name']); ?></td>
                <td><?php echo e($l['category_name']); ?></td>
                <td class="num">$<?php echo number_format($l['current_bid'], 2); ?></td>
                <td><?php echo statusBadge($l['status']); ?></td>
                <td><div class="actions"><a href="listings.php?delete=<?php echo (int)$l['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this listing?')">Delete</a></div></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<?php include '../partials/footer.php'; ?>
