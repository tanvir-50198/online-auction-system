<?php
require_once '../../includes/session.php';
require_once '../../config/database.php';
require_once '../../models/ListingModel.php';
require_once '../../models/BidModel.php';
require_once '../../includes/functions.php';
requireRole('seller');

closeExpiredListings($conn);
$listings = getSellerListings($conn, $_SESSION['user_id']);

$activeCount = 0; $totalBids = 0; $totalValue = 0.0;
foreach ($listings as $l) {
    $totalBids += (int)$l['bid_count'];
    if ($l['status'] == 'active') { $activeCount++; $totalValue += (float)$l['current_bid']; }
}
?>
<?php include '../partials/header.php'; ?>
<?php include '../partials/seller_nav.php'; ?>

<div class="page-head">
    <div>
        <p class="eyebrow">Seller dashboard</p>
        <h1>Welcome back, <?php echo e($_SESSION['user_name']); ?></h1>
        <p>Here is how your auctions are performing right now.</p>
    </div>
    <div class="page-head-actions">
        <a href="create_listing.php" class="btn btn-primary"><?php echo icon('plus', 16); ?> Create auction</a>
    </div>
</div>

<div class="stats">
    <div class="stat-card">
        <span class="stat-icon"><?php echo icon('package', 22); ?></span>
        <div><div class="stat-value"><?php echo count($listings); ?></div><div class="stat-label">Total listings</div></div>
    </div>
    <div class="stat-card">
        <span class="stat-icon green"><?php echo icon('bolt', 22); ?></span>
        <div><div class="stat-value"><?php echo $activeCount; ?></div><div class="stat-label">Live auctions</div></div>
    </div>
    <div class="stat-card">
        <span class="stat-icon blue"><?php echo icon('gavel', 22); ?></span>
        <div><div class="stat-value"><?php echo $totalBids; ?></div><div class="stat-label">Bids received</div></div>
    </div>
    <div class="stat-card">
        <span class="stat-icon"><?php echo icon('dollar', 22); ?></span>
        <div><div class="stat-value">$<?php echo number_format($totalValue, 0); ?></div><div class="stat-label">Value of live bids</div></div>
    </div>
</div>

<div class="section-head">
    <h2>Your auction listings</h2>
    <a href="manage_listings.php" class="btn btn-ghost btn-sm">Manage all <?php echo icon('arrow', 14); ?></a>
</div>

<?php if (empty($listings)): ?>
    <div class="empty-state">
        <span class="empty-icon"><?php echo icon('package', 26); ?></span>
        <h3>You have no listings yet</h3>
        <p>Create your first auction and start receiving bids.</p>
        <a href="create_listing.php" class="btn btn-primary">Create auction</a>
    </div>
<?php else: ?>
    <div class="listings-grid" id="seller-dashboard">
        <?php foreach ($listings as $listing): ?>
            <article class="listing-card">
                <a class="listing-media" href="results.php?listing=<?php echo (int)$listing['id']; ?>">
                    <img src="<?php echo listingImageUrl($listing['image']); ?>" alt="<?php echo e($listing['title']); ?>" loading="lazy">
                    <span class="badge status-<?php echo e(strtolower($listing['status'])); ?>"><?php echo e(ucfirst($listing['status'])); ?></span>
                    <span class="chip chip-time" data-end="<?php echo e($listing['end_datetime']); ?>"><?php echo icon('clock', 14); ?> <span class="t">--</span></span>
                </a>
                <div class="listing-body">
                    <h3 class="listing-title"><a href="results.php?listing=<?php echo (int)$listing['id']; ?>"><?php echo e($listing['title']); ?></a></h3>
                    <div class="listing-meta">
                        <div><span class="label">Current bid</span><span class="price">$<span id="bid-<?php echo (int)$listing['id']; ?>"><?php echo number_format($listing['current_bid'], 2); ?></span></span></div>
                        <div class="right"><span class="label">Bids</span><span class="num" id="count-<?php echo (int)$listing['id']; ?>"><?php echo (int)$listing['bid_count']; ?></span></div>
                    </div>
                </div>
                <div class="listing-foot">
                    <?php if ($listing['bid_count'] == 0): ?>
                        <a href="edit_listing.php?id=<?php echo (int)$listing['id']; ?>" class="btn btn-sm">Edit</a>
                        <a href="manage_listings.php?delete=<?php echo (int)$listing['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this listing?')">Delete</a>
                    <?php else: ?>
                        <a href="results.php?listing=<?php echo (int)$listing['id']; ?>" class="btn btn-sm">View results</a>
                    <?php endif; ?>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<script src="../../assets/js/seller.js"></script>
<?php include '../partials/footer.php'; ?>
