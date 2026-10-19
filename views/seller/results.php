<?php
require_once '../../includes/session.php';
require_once '../../config/database.php';
require_once '../../models/ListingModel.php';
require_once '../../models/BidModel.php';
requireRole('seller');

$listing_id = isset($_GET['listing']) ? (int)$_GET['listing'] : 0;
$listing = getListingById($conn, $listing_id);
if (!$listing || $listing['seller_id'] != $_SESSION['user_id']) {
    header("Location: dashboard.php");
    exit();
}

$bids = getBidHistory($conn, $listing_id);
$winner = null;
if (!empty($bids)) {
    $winner = $bids[0]; // highest bid
}
?>
<?php include '../partials/header.php'; ?>
<?php include '../partials/seller_nav.php'; ?>

<nav class="breadcrumb" aria-label="Breadcrumb">
    <a href="manage_listings.php">My listings</a><span>/</span><span><?php echo e($listing['title']); ?></span>
</nav>

<div class="page-head">
    <div>
        <h1>Auction results</h1>
        <p><?php echo e($listing['title']); ?></p>
    </div>
    <div class="page-head-actions"><?php echo statusBadge($listing['status']); ?></div>
</div>

<div class="result-grid">
    <div class="meta-tile"><span>Starting price</span><strong>$<?php echo number_format($listing['starting_price'], 2); ?></strong></div>
    <div class="meta-tile"><span><?php echo $listing['status'] == 'closed' ? 'Final bid' : 'Current bid'; ?></span><strong>$<?php echo number_format($listing['current_bid'], 2); ?></strong></div>
    <div class="meta-tile"><span>Total bids</span><strong><?php echo count($bids); ?></strong></div>
    <div class="meta-tile"><span>Ends</span><strong><?php echo date('M d, Y H:i', strtotime($listing['end_datetime'])); ?></strong></div>
</div>

<?php if ($winner): ?>
    <div class="winner-card">
        <span class="avatar"><?php echo e(strtoupper(substr($winner['buyer_name'], 0, 1))); ?></span>
        <div class="grow">
            <span class="eyebrow" style="margin:0"><?php echo $listing['status'] == 'closed' ? 'Winning buyer' : 'Highest bidder'; ?></span>
            <h3><?php echo e($winner['buyer_name']); ?></h3>
            <p class="muted">Bid amount: $<?php echo number_format($winner['bid_amount'], 2); ?></p>
        </div>
        <?php if ($listing['status'] == 'closed'): ?>
            <a href="add_review.php?listing_id=<?php echo $listing_id; ?>&buyer_id=<?php echo (int)$winner['buyer_id']; ?>" class="btn btn-primary">Review buyer</a>
        <?php endif; ?>
    </div>

    <div class="section-head" style="margin-top:8px"><h2>All bids</h2><span class="count"><?php echo count($bids); ?></span></div>
    <div class="table-wrap">
        <table class="data-table">
            <thead><tr><th>#</th><th>Bidder</th><th>Amount</th><th>Time</th></tr></thead>
            <tbody>
            <?php foreach ($bids as $i => $bid): ?>
                <tr class="<?php echo $i === 0 ? 'row-top' : ''; ?>">
                    <td class="muted"><?php echo $i + 1; ?></td>
                    <td class="cell-title"><?php echo e($bid['buyer_name']); ?></td>
                    <td class="num">$<?php echo number_format($bid['bid_amount'], 2); ?></td>
                    <td class="muted"><?php echo date('M d, Y H:i', strtotime($bid['created_at'])); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="empty-state">
        <span class="empty-icon"><?php echo icon('gavel', 26); ?></span>
        <h3>No bids placed yet</h3>
        <p>Buyers have not bid on this auction so far.</p>
    </div>
<?php endif; ?>

<div class="form-actions" style="margin-top:28px">
    <a href="dashboard.php" class="btn">Back to dashboard</a>
</div>

<?php include '../partials/footer.php'; ?>
