<?php
require_once '../../includes/session.php';
require_once '../../includes/functions.php';
require_once '../../config/database.php';
require_once '../../models/ListingModel.php';
require_once '../../models/BidModel.php';
require_once '../../models/WatchlistModel.php';
requireRole('buyer');

$listing_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$listing = getListingById($conn, $listing_id);
if (!$listing) {
    header("Location: browse.php");
    exit();
}

$bidHistory = getBidHistory($conn, $listing_id);
$inWatchlist = isInWatchlist($conn, $_SESSION['user_id'], $listing_id);

// Handle watchlist toggle
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['toggle_watchlist'])) {
    if ($inWatchlist) {
        removeFromWatchlist($conn, $_SESSION['user_id'], $listing_id);
        $inWatchlist = false;
    } else {
        addToWatchlist($conn, $_SESSION['user_id'], $listing_id);
        $inWatchlist = true;
    }
    header("Location: details.php?id=$listing_id");
    exit();
}
?>
<?php
$isLive  = ($listing['status'] == 'active' && strtotime($listing['end_datetime']) > time());
$isOwner = ($listing['seller_id'] == $_SESSION['user_id']);
$minBid  = number_format($listing['current_bid'] + 0.01, 2, '.', '');
?>
<?php include '../partials/header.php'; ?>
<?php include '../partials/buyer_nav.php'; ?>

<nav class="breadcrumb" aria-label="Breadcrumb">
    <a href="browse.php">Auctions</a><span>/</span><span><?php echo e($listing['title']); ?></span>
</nav>

<div class="detail-layout">
    <div class="detail-gallery">
        <div class="detail-image">
            <img src="<?php echo listingImageUrl($listing['image']); ?>" alt="<?php echo e($listing['title']); ?>">
        </div>
        <div class="card detail-desc">
            <h3>About this item</h3>
            <p><?php echo $listing['description'] !== '' ? nl2br(e($listing['description'])) : 'The seller has not added a description.'; ?></p>
        </div>
    </div>

    <div class="detail-panel">
        <div>
            <div class="detail-tags">
                <?php echo $isLive ? statusBadge('active', 'Live') : statusBadge('closed', 'Ended'); ?>
                <span class="badge badge-muted"><?php echo e($listing['category_name']); ?></span>
            </div>
            <h1 class="detail-title" style="margin-top:12px"><?php echo e($listing['title']); ?></h1>
            <p class="seller-line" style="margin-top:6px">Sold by <strong><?php echo e($listing['seller_name']); ?></strong></p>
        </div>

        <div class="price-box">
            <span class="label">Current bid</span>
            <div class="big">$<span id="currentBid"><?php echo number_format($listing['current_bid'], 2); ?></span></div>
        </div>

        <div class="meta-grid">
            <div class="meta-tile"><span>Starting price</span><strong>$<?php echo number_format($listing['starting_price'], 2); ?></strong></div>
            <div class="meta-tile"><span>Total bids</span><strong><?php echo count($bidHistory); ?></strong></div>
            <div class="meta-tile"><span>Time left</span><strong id="countdown" data-end="<?php echo e($listing['end_datetime']); ?>" data-long>--</strong></div>
        </div>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-error" style="margin:0"><?php echo e($_GET['error']); ?></div>
        <?php endif; ?>
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success" style="margin:0"><?php echo icon('check', 18); ?> Your bid was placed successfully.</div>
        <?php endif; ?>

        <?php if ($isLive && !$isOwner): ?>
            <form method="POST" action="../../controllers/bid_controller.php" class="bid-form">
                <input type="hidden" name="listing_id" value="<?php echo $listing_id; ?>">
                <label for="bid_amount" style="font-weight:600;font-size:.86rem;color:var(--text-2)">Your bid</label>
                <div class="bid-form-row">
                    <div class="input-group">
                        <span class="input-prefix">$</span>
                        <input type="number" id="bid_amount" name="bid_amount" step="0.01" min="<?php echo $minBid; ?>" placeholder="<?php echo $minBid; ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg">Place bid</button>
                </div>
                <span class="field-hint">Minimum bid: $<?php echo number_format($listing['current_bid'] + 0.01, 2); ?></span>
            </form>
        <?php elseif ($isOwner): ?>
            <div class="alert alert-info" style="margin:0" data-persist>This is your own listing, so you cannot bid on it.</div>
        <?php elseif (!$isLive): ?>
            <div class="alert alert-info" style="margin:0" data-persist>This auction has ended.</div>
        <?php endif; ?>

        <form method="POST">
            <button type="submit" name="toggle_watchlist" class="btn btn-secondary btn-block">
                <?php echo icon('heart', 16); ?>
                <?php echo $inWatchlist ? 'Remove from watchlist' : 'Add to watchlist'; ?>
            </button>
        </form>
    </div>
</div>

<div class="section-head">
    <h2>Bid history</h2>
    <span class="count"><?php echo count($bidHistory); ?> bid<?php echo count($bidHistory) === 1 ? '' : 's'; ?></span>
</div>

<?php if (empty($bidHistory)): ?>
    <div class="empty-state">
        <span class="empty-icon"><?php echo icon('gavel', 26); ?></span>
        <h3>No bids yet</h3>
        <p>Be the first to place a bid on this item.</p>
    </div>
<?php else: ?>
    <div class="table-wrap">
        <table class="data-table">
            <thead><tr><th>#</th><th>Bidder</th><th>Amount</th><th>Time</th></tr></thead>
            <tbody>
            <?php foreach ($bidHistory as $i => $bid): ?>
                <tr class="<?php echo $i === 0 ? 'row-top' : ''; ?>">
                    <td class="muted"><?php echo $i + 1; ?></td>
                    <td>
                        <div class="cell-user">
                            <span class="avatar"><?php echo e(strtoupper(substr($bid['buyer_name'], 0, 1))); ?></span>
                            <span class="cell-title"><?php echo e($bid['buyer_name']); ?></span>
                            <?php if ($i === 0): ?><span class="badge badge-accent">Highest</span><?php endif; ?>
                        </div>
                    </td>
                    <td class="num">$<?php echo number_format($bid['bid_amount'], 2); ?></td>
                    <td class="muted"><?php echo date('M d, Y H:i', strtotime($bid['created_at'])); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php include '../partials/footer.php'; ?>
