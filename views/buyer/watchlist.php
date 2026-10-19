<?php
require_once '../../includes/session.php';
require_once '../../config/database.php';
require_once '../../models/WatchlistModel.php';
requireRole('buyer');

if (isset($_GET['remove'])) {
    removeFromWatchlist($conn, $_SESSION['user_id'], (int)$_GET['remove']);
    header("Location: watchlist.php");
    exit();
}

$watchlist = getWatchlist($conn, $_SESSION['user_id']);
?>
<?php include '../partials/header.php'; ?>
<?php include '../partials/buyer_nav.php'; ?>

<div class="page-head">
    <div>
        <h1>My watchlist</h1>
        <p>Auctions you are keeping an eye on.</p>
    </div>
</div>

<?php if (empty($watchlist)): ?>
    <div class="empty-state">
        <span class="empty-icon"><?php echo icon('heart', 26); ?></span>
        <h3>Your watchlist is empty</h3>
        <p>Tap "Add to watchlist" on any auction to follow it here.</p>
        <a href="browse.php" class="btn btn-primary">Browse auctions</a>
    </div>
<?php else: ?>
    <div class="listings-grid">
        <?php foreach ($watchlist as $item): ?>
            <?php
            $footer = '<a href="details.php?id=' . (int)$item['id'] . '" class="btn btn-primary btn-sm">View &amp; bid</a>'
                    . '<a href="watchlist.php?remove=' . (int)$item['id'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Remove from watchlist?\')">Remove</a>';
            echo renderListingCard($item, $footer);
            ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include '../partials/footer.php'; ?>
