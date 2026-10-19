<?php
require_once '../../includes/session.php';
require_once '../../config/database.php';
require_once '../../models/ListingModel.php';
require_once '../../models/BidModel.php';
requireRole('seller');

$listings = getSellerListings($conn, $_SESSION['user_id']);
?>
<?php include '../partials/header.php'; ?>
<?php include '../partials/seller_nav.php'; ?>

<div class="page-head">
    <div>
        <h1>Bid monitoring</h1>
        <p>Live view of bids on your auctions. Updates every few seconds.</p>
    </div>
</div>

<?php if (empty($listings)): ?>
    <div class="empty-state">
        <span class="empty-icon"><?php echo icon('gavel', 26); ?></span>
        <h3>Nothing to monitor yet</h3>
        <p>Once you create an auction, its bids will show up here.</p>
        <a href="create_listing.php" class="btn btn-primary">Create auction</a>
    </div>
<?php else: ?>
    <div id="liveBids" class="bids-grid">
        <?php foreach ($listings as $listing): ?>
            <div class="bid-card">
                <div class="bid-card-head">
                    <h3><?php echo e($listing['title']); ?></h3>
                    <?php echo statusBadge($listing['status']); ?>
                </div>
                <div class="kv"><span>Highest bid</span><strong class="gold">$<span id="high-<?php echo (int)$listing['id']; ?>"><?php echo number_format($listing['current_bid'], 2); ?></span></strong></div>
                <div class="kv"><span>Total bids</span><strong id="total-<?php echo (int)$listing['id']; ?>"><?php echo (int)$listing['bid_count']; ?></strong></div>
                <div class="kv"><span>Ends</span><strong><?php echo date('M d, H:i', strtotime($listing['end_datetime'])); ?></strong></div>
                <a href="results.php?listing=<?php echo (int)$listing['id']; ?>" class="btn btn-sm">View details</a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<script src="../../assets/js/seller.js"></script>
<?php include '../partials/footer.php'; ?>
