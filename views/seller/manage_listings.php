<?php
require_once '../../includes/session.php';
require_once '../../config/database.php';
require_once '../../models/ListingModel.php';
require_once '../../includes/functions.php';
requireRole('seller');

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $listing = getListingById($conn, $id);
    if ($listing && $listing['seller_id'] == $_SESSION['user_id'] && !hasBids($conn, $id)) {
        deleteListing($conn, $id);
    }
    header("Location: manage_listings.php");
    exit();
}

$listings = getSellerListings($conn, $_SESSION['user_id']);
?>
<?php include '../partials/header.php'; ?>
<?php include '../partials/seller_nav.php'; ?>

<div class="page-head">
    <div>
        <h1>My listings</h1>
        <p>Edit, review or remove your auctions. Listings with bids are locked.</p>
    </div>
    <div class="page-head-actions"><a href="create_listing.php" class="btn btn-primary"><?php echo icon('plus', 16); ?> Create auction</a></div>
</div>

<?php if (isset($_GET['created'])): ?>
    <div class="alert alert-success"><?php echo icon('check', 18); ?> Auction created successfully.</div>
<?php endif; ?>
<?php if (isset($_GET['updated'])): ?>
    <div class="alert alert-success"><?php echo icon('check', 18); ?> Auction updated successfully.</div>
<?php endif; ?>

<?php if (empty($listings)): ?>
    <div class="empty-state">
        <span class="empty-icon"><?php echo icon('package', 26); ?></span>
        <h3>No listings yet</h3>
        <p>Create your first auction to get started.</p>
        <a href="create_listing.php" class="btn btn-primary">Create auction</a>
    </div>
<?php else: ?>
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr><th>Item</th><th>Current bid</th><th>Ends</th><th>Bids</th><th>Status</th><th class="right">Actions</th></tr>
            </thead>
            <tbody>
            <?php foreach ($listings as $listing): ?>
                <tr>
                    <td>
                        <div class="cell-user">
                            <img class="thumb" src="<?php echo listingImageUrl($listing['image']); ?>" alt="">
                            <div>
                                <div class="cell-title"><?php echo e($listing['title']); ?></div>
                                <div class="cell-sub"><?php echo e($listing['category_name']); ?></div>
                            </div>
                        </div>
                    </td>
                    <td class="num">$<?php echo number_format($listing['current_bid'], 2); ?></td>
                    <td class="muted"><?php echo date('M d, Y H:i', strtotime($listing['end_datetime'])); ?></td>
                    <td class="num"><?php echo (int)$listing['bid_count']; ?></td>
                    <td><?php echo statusBadge($listing['status']); ?></td>
                    <td>
                        <div class="actions">
                            <a href="results.php?listing=<?php echo (int)$listing['id']; ?>" class="btn btn-sm">Results</a>
                            <?php if ($listing['bid_count'] == 0): ?>
                                <a href="edit_listing.php?id=<?php echo (int)$listing['id']; ?>" class="btn btn-sm">Edit</a>
                                <a href="manage_listings.php?delete=<?php echo (int)$listing['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this listing?')">Delete</a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php include '../partials/footer.php'; ?>
